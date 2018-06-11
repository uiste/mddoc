var searchDatas = <?=json_encode($searchDatas)?>;

String.prototype.indexOf = function(f){
    var rt = this.match(eval("/"+ f +"/i"));
    return (rt == null) ? -1:rt.index;
}

function switchSearchNode(a)
{
	if ('pushState' in history)
	{
		getChapter(a.attr('href'));
		history.pushState('', '', a.attr('url'));
		$('#treeSearch a').removeClass('curSelectedNode');
		a.addClass('curSelectedNode');
	}
	else
	{
		location = treeNode.url;
	}
}

function searchArticle(keyword)
{
    var keywords = keyword.split(' ');
    var tSearchDatas = searchDatas;
    var result = [];
    keywords.forEach(function(kw){
		if('' === kw)
		{
			return;
		}
        result = [];
        tSearchDatas.forEach(function(item, index){
            if(item.content.indexOf(kw) > -1 || item.title.indexOf(kw) > -1)
            {
                result.push(item);
            }
        });
        tSearchDatas = result;
    });
    return result;
}

var searchTimer = null;
function parseSearch()
{
    $('#search-keyword').on('input', function(){
		if(null != searchTimer)
		{
			clearTimeout(searchTimer);
			searchTimer = null;
		}
		var inputKeyword = $(this);
		searchTimer = setTimeout(function(){
			var result = searchArticle(inputKeyword.val());
			if(result.length > 0)
			{
				$('.searchResultNone').hide();
			}
			else
			{
				$('.searchResultNone').show();
			}
			layui.laytpl($('#searchListTemplate').html()).render(result, function(html){
				$('#treeSearch').html(html);
			});
			searchTimer = null;
		}, 200);
		return false;
    });
}

$(function(){

	$('body').on('click', '#treeSearch a', function(e){
		switchSearchNode($(this));
		return false;
	});

	parseSearch();

})