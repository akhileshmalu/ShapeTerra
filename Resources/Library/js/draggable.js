$(".columns").sortable({
    items: "data-row",
    appendTo: "parent",
    helper: "clone"
}).disableSelection();
