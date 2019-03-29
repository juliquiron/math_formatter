Drupal.behaviors.asyncMathFormatter = {
  attach: function (context, settings) {
    jQuery('math[data-evaluate="async"]', context).each(function () {
      jQuery(this).one('mouseenter', function () {
        let $this = jQuery(this);
        if (!$this.attr('data-evaluated')) {
          jQuery.get({
            url: "/calculator/evaluate?_format=json",
            contentType: 'application/json',
            data: {
              expression: $this.text()
            }
          }).done(function (res){
            $this.text($this.text() + ' = ' + res.value);
            $this.attr('data-evaluated', true);
          });
        }
      });
    });
  }
}
