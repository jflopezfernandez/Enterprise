
jQuery.noConflict();

(function ( $ ) {
    $(document).ready(function () {
        $("h1").on("click", function () {
            alert("You've clicked the page header.");
        });
    
        $("h2").on("click", function () {
            alert("You've clicked on a section header.");
        });
    
        $("p").on("click", function () {
            alert("You've clicked on a paragraph element.");
        });

        $("h1").attr({
            title: "This is the page title"
        });
    });
})( jQuery );
