var o = 
{
'initTb': 
	function ()
	{
		var switched = false;
	    if (($(window).width() < 767) && !switched )
	    {
	    	console.log('sssss');
	    	switched = true;
	    	$("table.responsive").each(function(i, element) {
	    		o.splitTb($(element));
	    	});
	    	return true;
	    }
	    else if (switched && ($(window).width() > 767)) 
	    {
	    	switched = false;
	    	$("table.responsive").each(function(i, element) {
	    		o.unsplitTb($(element));
	    	});
	    }
	},
'splitTb': 
	function (original)
	{
		original.wrap("<div class='table-wrapper' />");
		
		var copy = original.clone();
		copy.find("td:not(:first-child), th:not(:first-child)").css("display", "none");
		copy.removeClass("responsive");
		
		original.closest(".table-wrapper").append(copy);
		copy.wrap("<div class='pinned' />");
		original.wrap("<div class='scrollable' />");
	
		o.setCellHeights(original, copy);
	},
'unsplitTb':
	function (original)
	{
		original.closest(".table-wrapper").find(".pinned").remove();
		original.unwrap();
		original.unwrap();
	},
'setCellHeights':
	function (original, copy)
	{
		var tr = original.find('tr'),
	    tr_copy = copy.find('tr'),
	    heights = [];
	
		tr.each(function (index) {
			var self = $(this),
		    tx = self.find('th, td');
		
			tx.each(function () {
				var height = $(this).outerHeight(true);
		    	heights[index] = heights[index] || 0;
		    	if (height > heights[index]) heights[index] = height;
			});
		});
		
		tr_copy.each(function (index) {
		  $(this).height(heights[index]);
		});
	},
'init': 
	function (params)
	{
		$.ajax({
			url: o.AjaxUrl,
			type: 'POST',
			data: params,
			dataType: 'json',
			success: function (data) 
			{
				o.ElementList.html(o.ContentHtml(data));
				o.PaginationOptions.totalPages = Math.ceil(data.total / o.NumberOfPage);
				o.Pagination(o.ElementPage);
				o.initTb();
			}
		});
	},
'AjaxUrl':'',
'ElementPage':'',
'ElementList':'',
'NumberOfPage':8,
'Pagination':
	function (element)
	{
		element.bootstrapPaginator(o.PaginationOptions);	
	},
'PaginationOptions':
	{
		bootstrapMajorVersion:3,
	   	currentPage: 1,
	   	numberOfPages: 5,
	    pageUrl: function (type, page, current) {
		    o.PaginationOptions.currentPage = current;
			return "javascript:void(0);";
		}
	}
};