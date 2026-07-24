-- Adds a composite index covering the near-universal
-- `WHERE listening.user_id = ... AND listening.date BETWEEN ? AND ?`
-- filter used throughout music_helper.php / listening_helper.php.
-- Currently `listening` only has single-column indexes on user_id and
-- album_id (see statster.sql), so this filter can't use a single index
-- range scan. Deliberately just this one index, not a broader pass,
-- given the production app server's limited disk.
--
-- Review before running. Not applied automatically.

ALTER TABLE `listening`
  ADD INDEX `user_id_date` (`user_id`, `date`);
