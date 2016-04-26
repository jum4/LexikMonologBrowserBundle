$(function() {
    $('.datepicker').datepicker({
        format: 'mm/dd/yyyy'
    });

    $('.json').each(function() {
        var $this = $(this);

        const formatter = new JSONFormatter(JSON.parse($this.text()), 3, {hoverPreviewEnabled: true});

        $this.after(formatter.render());
    });
});
