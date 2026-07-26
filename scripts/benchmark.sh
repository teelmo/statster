#!/usr/bin/env bash
# Read-only local benchmark for statster.local. Records before/after numbers for the
# consolidation/speed pass (see the plan discussed in-session): request concurrency
# (session-lock serialization), page weight/timing, and the cumulative-listening query.
#
# Usage: scripts/benchmark.sh [base_url]

set -u

BASE_URL="${1:-http://statster.local}"
BENCH_PAGE="$BASE_URL/user/teelmo"
COOKIE_JAR="$(mktemp)"
trap 'rm -f "$COOKIE_JAR"' EXIT

# Warm the session cookie so every request below shares one session id, matching
# how a real browser tab fires concurrent AJAX calls against a single session.
curl -s -o /dev/null -c "$COOKIE_JAR" -b "$COOKIE_JAR" "$BASE_URL/"

echo "=== 1. Concurrency check (targets Phase 1: session-lock serialization) ==="
ENDPOINTS=(
  "/api/listening/get/cumulative"
  "/api/listener/get"
  "/api/genre/get"
  "/api/album/get"
  "/api/fromOthers"
  "/api/fan/get"
  "/api/love/get"
)

echo "-- sequential (sum of individual times) --"
seq_total=0
for endpoint in "${ENDPOINTS[@]}"; do
  t=$(curl -s -o /dev/null -b "$COOKIE_JAR" -w "%{time_total}" "$BASE_URL$endpoint")
  echo "$endpoint: ${t}s"
  seq_total=$(echo "$seq_total + $t" | bc)
done
echo "sequential sum: ${seq_total}s"
echo

echo "-- parallel (shared session cookie, fired concurrently) --"
parallel_start=$(date +%s.%N)
pids=()
for endpoint in "${ENDPOINTS[@]}"; do
  curl -s -o /dev/null -b "$COOKIE_JAR" "$BASE_URL$endpoint" &
  pids+=($!)
done
for pid in "${pids[@]}"; do
  wait "$pid"
done
parallel_end=$(date +%s.%N)
parallel_total=$(echo "$parallel_end - $parallel_start" | bc)
echo "parallel wall time: ${parallel_total}s"
echo "(serialized behavior: parallel wall time approx = sequential sum. fixed behavior: approx = slowest single call)"
echo

echo "=== 2. Page-weight/timing check (targets Phase 4) ==="
echo "page: $BENCH_PAGE"
curl -s -o /dev/null -b "$COOKIE_JAR" -w "ttfb=%{time_starttransfer}s total=%{time_total}s size=%{size_download} bytes\n" "$BENCH_PAGE"
echo

echo "=== 3. Cumulative-listening endpoint timing (targets Phase 5) ==="
for i in 1 2 3; do
  curl -s -o /dev/null -b "$COOKIE_JAR" -w "run $i: total=%{time_total}s\n" "$BASE_URL/api/listening/get/cumulative"
done
