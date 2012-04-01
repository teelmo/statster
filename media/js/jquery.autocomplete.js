/**
*  Ajax Autocomplete for jQuery, version 1.1.3
*  (c) 2010 Tomas Kirda
*
*  Ajax Autocomplete for jQuery is freely distributable under the terms of an MIT-style license.
*  For details, see the web site: http://www.devbridge.com/projects/autocomplete/jquery/
*
*  Last Review: 04/19/2010
*/

/*jslint onevar: true, evil: true, nomen: true, eqeqeq: true, bitwise: true, regexp: true, newcap: true, immed: true */
/*global window: true, document: true, clearInterval: true, setInterval: true, jQuery: true */

(function($) {

  var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');

  function fnFormatResult(value, data, currentValue) {
    var pattern = '(' + currentValue.replace(reEscape, '\\$1') + ')';
    return value.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>');
  }

  function Autocomplete(el, options) {
    this.el = $(el);
    this.el.attr('autocomplete', 'off');
    this.suggestions = [];
    this.data = [];
    this.badQueries = [];
    this.selectedIndex = -1;
    this.currentValue = this.el.val();
    this.intervalId = 0;
    this.cachedResponse = [];
    this.onChangeInterval = null;
    this.ignoreValueChange = false;
    this.serviceUrl = options.serviceUrl;
    this.isLocal = false;
    //statster{
    this.images = [];
    this.titles = [];
    this.response = null;
    this.extraParams = options.extraParams;
    this.selectValue = null;
    this.contentLength = 0;
    
    //}
    this.options = {
      autoSubmit: false,
      minChars: 1,
      maxHeight: 300,
      deferRequestBy: 0,
      width: 0,
      highlight: true,
      params: {},
      fnFormatResult: fnFormatResult,
      delimiter: null,
      zIndex: 9999,
      //{
      list:false, /* down arrow key and mouse click lists all results */
      /*cache:false,*/
      observableElement:null, /* {nameAttribute: idAttribute} */
      selectFirst: false,
      images:false,
      imageClass: 'middle rightMarginSearch',
      split: false /* after comma autosuggestions resets */,
      extraParams: {},
      headings: false,
      pos: {x:0,y:2},
      widen: 0
      //}
    };
    this.initialize();
    this.setOptions(options);
    if (this.options.split) {
      this.options.delimiter = ',';
    }
  }
  
  $.fn.autocomplete = function(options) {
    return new Autocomplete(this.get(0)||$('<input />'), options);
  };

  Autocomplete.prototype = {

    killerFn: null,

    initialize: function(options) {

      var me, uid, autocompleteElId;
      me = this;
      uid = Math.floor(Math.random()*0x100000).toString(16);
      autocompleteElId = 'Autocomplete_' + uid;

      this.killerFn = function(e) {
        if ($(e.target).parents('.autocomplete').size() === 0) {
          me.killSuggestions();
          me.disableKillerFn();
        }
      };

      if (!this.options.width) { this.options.width = this.el.width(); }
      this.mainContainerId = 'AutocompleteContainter_' + uid;

      $('<div id="' + this.mainContainerId + '" style="position:absolute;z-index:9999;"><div class="autocomplete-w1"><div class="autocomplete" id="' + autocompleteElId + '" style="display:none; width:300px;"></div></div></div>').appendTo('body');

      this.container = $('#' + autocompleteElId);
      this.fixPosition();
      if (window.opera) {
        this.el.keypress(function(e) { me.onKeyPress(e); });
      } else {
        this.el.keydown(function(e) { me.onKeyPress(e); });
      }
      this.el.keyup(function(e) { me.onKeyUp(e); });
      this.el.blur(function() { me.enableKillerFn(); });
      this.el.focus(function() { me.fixPosition(); });
      this.el.click(function() { me.showList(); });
    },
    
    setOptions: function(options){
      var o = this.options;
      $.extend(o, options);
      this.fixPosition(); // lisätty, jotta saadaan uudetta asetukset voimaan
      if(o.lookup){
        this.isLocal = true;
        if($.isArray(o.lookup)){ o.lookup = { suggestions:o.lookup, data:[] }; }
      }
      $('#'+this.mainContainerId).css({ zIndex:o.zIndex });
      this.container.css({ maxHeight: o.maxHeight + 'px', width:o.width+this.options.widen });
    },
    
    clearCache: function(){
      this.cachedResponse = [];
      this.badQueries = [];
    },
    
    disable: function(){
      this.disabled = true;
    },
    
    enable: function(){
      this.disabled = false;
    },
    
    showList: function() {
      if( this.options.list ) {
        if( !this.enabled ) {
          this.getSuggestions('');
        }
        else {
          this.hide();
        }
      }
    },
  
    fixPosition: function() {
      var offset = this.el.offset();
      $('#' + this.mainContainerId).css({
        top: (offset.top + this.el.innerHeight()+this.options.pos.y) + 'px', 
        left: (offset.left+this.options.pos.x) + 'px' 
      });
    },

    enableKillerFn: function() {
      var me = this;
      $(document).bind('click', me.killerFn);
    },

    disableKillerFn: function() {
      var me = this;
      $(document).unbind('click', me.killerFn);
    },

    killSuggestions: function() {
      var me = this;
      this.stopKillSuggestions();
      this.intervalId = window.setInterval(function() { me.hide(); me.stopKillSuggestions(); }, 300);
    },

    stopKillSuggestions: function() {
      window.clearInterval(this.intervalId);
    },

    onKeyPress: function(e) {
      //if (this.disabled || !this.enabled) { return; }
      if( this.options.list && e.keyCode == 40 && this.selectedIndex === -1 ) {
          this.getSuggestions(this.el.val());
          this.selectedIndex = this.options.selectFirst ? 0 : -1;
      }
      if (!this.enabled) {
        return;
      }
      // return will exit the function
      // and event will not be prevented
      switch (e.keyCode) {
        case 27: //KEY_ESC:
          this.el.val(this.currentValue);
          this.hide();
          break;
        case 9: //KEY_TAB:
        case 13: //KEY_RETURN:
          if (this.selectedIndex === -1) {
            this.hide();
            return;
          }
          this.select(this.selectedIndex);
          if(e.keyCode === 9){ return; }
          break;
        case 38: //KEY_UP:
          this.moveUp();
          break;
        case 40: //KEY_DOWN:
          this.moveDown();
          break;
        default:
          return;
      }
      e.stopImmediatePropagation();
      e.preventDefault();
    },

    onKeyUp: function(e) {
      if(this.disabled){ return; }
      switch (e.keyCode) {
        case 38: //KEY_UP:
        case 40: //KEY_DOWN:
          return;
        case 188: // on merkki "," (pilkku)
          this.hide();
        break;
      }
      clearInterval(this.onChangeInterval);
      if (this.currentValue !== this.el.val()) {
        if (this.options.deferRequestBy > 0) {
          // Defer lookup in case when value changes very quickly:
          var me = this;
          this.onChangeInterval = setInterval(function() { me.onValueChange(); }, this.options.deferRequestBy);
        } else {
          this.onValueChange();
        }
      }
    },
    
    onValueChange: function() {
      clearInterval(this.onChangeInterval);
      this.currentValue = this.el.val();
      var q = this.getQuery(this.currentValue);
      if( this.selectedIndex == -1 && this.options.selectFirst ) {
        this.selectedIndex = 0;
      }
      if (this.ignoreValueChange) {
        this.ignoreValueChange = false;
        return;
      }
      if (q === '' || q.length < this.options.minChars) {
        this.hide();
      } else {
        this.getSuggestions(q);
      }
    },

    getQuery: function(val) {
      var d, arr;
      d = this.options.delimiter;
      if (!d) { return $.trim(val); }
      arr = val.split(d);
      return $.trim(arr[arr.length - 1]);
    },

    getSuggestionsLocal: function(q) {
      var ret, arr, len, val, i;
      arr = this.options.lookup;
      len = arr.suggestions.length;
      ret = { suggestions:[], data:[] };
      q = q.toLowerCase();
      for(i=0; i< len; i++){
        val = arr.suggestions[i];
        if(val.toLowerCase().indexOf(q) === 0){
          ret.suggestions.push(val);
          ret.data.push(arr.data[i]);
        }
      }
      return ret;
    },
    
    getSuggestions: function(q) {
      var me;
      if (!this.isBadQuery(q)) {
        me = this;
        me.options.params.query = q;
        this.getInputs();
        $.extend(me.options.params, this.extraParams);
        $.get(this.serviceUrl, me.options.params, function(txt) { me.processResponse(txt); }, 'text');
      } else {
        this.hide();
      }
    },

    isBadQuery: function(q) {
      var i = this.badQueries.length;
      while (i--) {
        if (q.indexOf(this.badQueries[i]) === 0) { return true; }
      }
      return false;
    },

    processResponse: function(text) {
      try { 
        this.response = jQuery.parseJSON(text);
      } catch(e) {}
            
      this.suggestions = [];
      this.data = [];
      if( this.response.content !== undefined && this.response.content !== null ) {
        this.contentLength = 0;
        // Yhdistetään otsikoihin jaetut tulokset
        for( name in this.response.content ) {
          this.suggestions = this.suggestions.concat(this.response.content[name].suggestions);
          this.data = this.data.concat(this.response.content[name].data);
          this.contentLength++;
        }
        if (this.response.query === this.getQuery(this.currentValue)) {
          this.suggest(); 
        }
      } else {
        this.badQueries.push(this.response.query);
        // piilotetaan lista kun response on tyhjä
        this.hide();
      }
    },
    
    hide: function() {
      this.enabled = false;
      this.selectedIndex = -1;
      this.container.hide();
    },
    
    suggest: function() {
      if (this.response.content === null) {
        this.hide();
        return;
      }
      var content = [];
      var image = '';
      var index = 0;
      var me, len, div, f, v, i, s, mOver, mClick;
      me = this;
      f = this.options.fnFormatResult;
      v = this.getQuery(this.currentValue);
      mOver = function(xi) { return function() { me.activate(xi); }; };
      mClick = function(xi) { return function() { me.select(xi); }; };
      this.container.hide().empty();
      var j = 0;
      for( name in this.response.content ) {
        len = this.response.content[name].suggestions.length;
        if( len > 0 ) {
          if( this.options.headings || this.contentLength > 1 ) {
            this.container.append('<span class="heading">' + name + '</span>');
          }
          for (i = 0; i < len; i++) {
            if( this.options.images ) {
              image = '<img src="' + this.response.content[name].images[i] + '" class="' + this.options.imageClass + '" />';
            }
            s = this.response.content[name].suggestions[i];
            title = this.response.content[name].titles[i];
            data = this.response.content[name].data[i];
            div = $((me.selectedIndex === i ? '<div class="selected"' : '<div')
                    + ' title="' + title + '">' + image + f(s, data, v) + '</div>');
            div.mouseover(mOver(j));
            div.click(mClick(j));
            this.container.append(div);
            j++;
          }
        }
      }
      this.enabled = true;
      this.container.show();
    },

    activate: function(index) {
      var divs, activeItem;
      // Ei haluta valita span-elementtejä
      divs = this.container.children('div');
      // Clear previous selection:
      if (this.selectedIndex !== -1 && divs.length > this.selectedIndex) {
        $(divs.get(this.selectedIndex)).removeClass();
      }
      this.selectedIndex = index;
      if (this.selectedIndex !== -1 && divs.length > this.selectedIndex) {
        activeItem = divs.get(this.selectedIndex);
        $(activeItem).addClass('selected');
      }
      return activeItem;
    },

    deactivate: function(div, index) {
      div.className = '';
      if (this.selectedIndex === index) { this.selectedIndex = -1; }
    },
    
    select: function(i) {
      var selectedValue, f;
      selectedValue = this.suggestions[i];
      
      if (selectedValue) {
        this.el.val(selectedValue);
        this.ignoreValueChange = true;
        //~ this.hide(); -> this.killSuggestions();
        this.killSuggestions();
        this.disableKillerFn();
        this.onSelect(i);
        if (this.options.autoSubmit) {
          f = this.el.parents('form');
          if (f.length > 0) { f.get(0).submit(); }
        }
      }
    },

    moveUp: function() {
      if (this.selectedIndex === -1) { return; }
      if (this.selectedIndex === 0) {
        this.container.children('div').get(0).className = '';
        this.selectedIndex = -1;
        this.el.val(this.currentValue);
        return;
      }
      this.adjustScroll(this.selectedIndex - 1);
    },

    moveDown: function() {
      if (this.selectedIndex === (this.suggestions.length - 1)) { return; }
      this.adjustScroll(this.selectedIndex + 1);
    },

    adjustScroll: function(i) {
      var activeItem, offsetTop, upperBound, lowerBound;
      activeItem = this.activate(i);
      offsetTop = activeItem.offsetTop;
      upperBound = this.container.scrollTop();
      lowerBound = upperBound + this.options.maxHeight - 25;
      if (offsetTop < upperBound) {
        this.container.scrollTop(offsetTop);
      } else if (offsetTop > lowerBound) {
        this.container.scrollTop(offsetTop - this.options.maxHeight + 25);
      }
      this.el.val(this.getValue(this.suggestions[i]));
    },

    onSelect: function(i) {
      var me, fn, s, d;
      me = this;
      fn = me.options.onSelect;
      s = me.suggestions[i];
      d = me.data[i];
      me.el.val(me.getValue(s));
      if ($.isFunction(fn)) { fn(s, d, me.el); }
    },
    
    getValue: function(value){
        var del, currVal, arr, me;
        me = this;
        del = me.options.delimiter;
        if (!del) { return value; }
        currVal = me.currentValue;
        arr = currVal.split(del);
        if (arr.length === 1) { return value; }
        return currVal.substr(0, currVal.length - arr[arr.length - 1].length) + ' ' + value;
    },
    
    // Lisätty
    getInputs: function() {
      if( this.options.observableElement != null ) {
        // clone koska en osaa luoda vastaavaa tyhjästä
        /* Prototype
        var tmp = Object.clone(this.options.observableElement);
        */
        var tmp = {};
        $.extend(tmp, this.options.observableElement);
        // for each, koska en osaa tehdäsamaa yhdelle elementille
        for( property in this.options.observableElement ) {
          /* Prototype
          tmp[property] = $F(this.options.observableElement[property]);
          */
          tmp[property] = $('#' + this.options.observableElement[property]).val()
          // Tyhjennetään cache vain, jos tarkkailtavan elementin arvo on muuttunut
          if( this.selectValue != tmp[property] ) {
            //~ this.cachedResponse = [];
            this.selectValue = tmp[property];
          }
          if( this.extraParams === undefined ) {
            this.extraParams = tmp;
          } else {
            $.extend(this.extraParams, tmp);
          }
          break;
        }
      }
      return;
    }
  };

}(jQuery));
