;(function($){$.fn.replaceholder=function(options){settings=jQuery.extend({focusColor:'#000',blurColor:'#aaa'},options);var $placeholder;(this.length>0)?$this=$(this):$this=$('input[placeholder]');return $this.each(function(){var $placeholder=$(this);if($placeholder.length>0){var attrPh=$placeholder.attr('placeholder');$placeholder.attr('value',attrPh).css('color',settings.blurColor).bind('focus',function(){var $this=$(this);if($this.val()===attrPh)$this.val('').css('color',settings.focusColor)}).bind('blur',function(){var $this=$(this);if($this.val()==='')$this.val(attrPh).css('color',settings.blurColor)})}})}})(jQuery);