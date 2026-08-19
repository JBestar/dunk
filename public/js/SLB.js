var SLB_cnt = 0;
function SLB(url, option)
{
	var defaults = {
		width	:'six wide column', // six wide column
		height	:600,
		caption	:' ',
		close	:true,
		draggable:true,
		handle	:'#SLB_caption',
		resizable: true
	};
	var settings = $.extend(defaults, option);

	var SLB_film	= document.getElementById('SLB_film');
	var SLB_content = document.getElementById('SLB_content');
	var SLB_loading = document.getElementById('SLB_loading');
	$('#SLB_wide').removeClass().addClass(settings.width);
	
	if(url) {
		SLB_film.style.top = 0;
		SLB_film.style.left = 0;
		SLB_film.style.display = "";

		if (document.documentElement.scrollHeight > document.body.scrollHeight) {
			SLB_film.style.height = document.documentElement.scrollHeight + 'px';
		} else {
			SLB_film.style.height = document.body.scrollHeight + 'px';
		}
		SLB_loading.style.display = "block";

		if($(window).height()<settings.height) {
			settings.height = $(window).height();
		}

		if (!SLB_content.style.left)
		{
			x = settings.width;
			y = settings.height;
			var window_left = $(window).width()/2-(x/2);
			var window_top  = $(window).height()/2-(y/2) + $(document).scrollTop();

			// SLB_content.style.left = (window_left+5)+"px";
			SLB_content.style.top = (window_top+30)+"px";
			// console.log("window_left=" + window_left);
		}

		SLB_content.onclick = '';
		if(settings.caption)
		{
			settings.handle = '.SLB_caption';
			SLB_content.innerHTML =  "<div class='SLB_caption'>"+settings.caption+"</div>";
		}
		if(settings.close)
		{
			SLB_content.innerHTML += "<div class='SLB_close' onclick='SLB();'></div>";
		}
		if(settings.draggable)
		{
			if(settings.handle!='') $("#SLB_content").draggable({ handle: settings.handle });
			else				    $("#SLB_content").draggable({containment:'#content'});
		}

		ifr_width = '100%';//'99%';
		ifr_height = '95%';//'90%';
		SLB_content.innerHTML +="<iframe id='SLB_iframe' src=" + url + " width="+ ifr_width +" height="+ ifr_height +" " +
			"class='SLB_center' marginwidth='0' marginheight='0' frameborder='0' vspace='0' hspace='0' allowTransparency=false style='border:2px solid #ffb08e; border-top:0px;'/></iframe>";

		//SLB_content.style.width = settings.width+ 'px';
		SLB_content.style.width = 'calc(100% - 25px)'; //'99%'
		// SLB_content.style.padding = '0 0 0 5px'; //'99%'
		if(settings.caption) settings.height += 30; //for caption
		SLB_content.style.height = settings.height+ 'px';

		if(settings.resizable)
		{
			$('#SLB_content').resizable({
				//alsoResize: "#SLB_iframe"
			});
		}
		$(SLB_content).slideDown(400);
	} else {
		//SLB_film.onclick = '';
		SLB_film.style.display = "none";

		SLB_film.style.height = '100%';
		SLB_film.style.width = '100%';

		SLB_content.style.left = '';
		SLB_content.style.top = '';
		SLB_content.style.width = '';
		SLB_content.style.height = '';
		SLB_content.innerHTML = "";
		SLB_content.onclick = function () { SLB() };
		SLB_content.className = '';

		// resizable 한 객체를 명시적으로 지워져야 두번째이후 생성된 SLB_content 에 resizable 이 먹힘.
		try {
			$("#SLB_content").resizable( "destroy" );
		} catch(e) {}

		SLB_loading.style.display = "none";
		SLB_cnt = 0;
		$(SLB_content).slideUp(200);
	}
}