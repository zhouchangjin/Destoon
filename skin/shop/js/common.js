//导航
$.fn.MainNav = function(){
	var self = $(this);
	var bgName = 'cur';
    $("#mainNav li").hover(function () {
        $("div", this).slideDown(200); $(this).addClass("cur");
    },
    function () {
        $(this).removeClass("cur"); $("div", this).slideUp(200);
    });
}

/*头部下拉*/
$.fn.headmenu = function(type){
	var self = $(this),list;
	if(type === 1){
		list = self.find('.menu');
	}else{
		list = self;
	}
	list.hover(function(){
		$(this).toggleClass('hover').find('.sub_menu').toggleClass('hide');
	});
}

/*总分类显隐*/
$.fn.Prolist = function(){
	var self = $(this);
	var curName = 'cur';
	self.delegate('.item','hover',function(){
		$(this).toggleClass("cur");
	});
}

/*tab*/
$.fn.TabADS = function(){
	var obj = $(this);
	var currentClass = "select";
	var tabs = obj.find(".tab_hd").find("li");
	var conts = obj.find(".tab_cont");
	var t;
	tabs.eq(0).addClass(currentClass);
	conts.hide();
	conts.eq(0).show();
	tabs.each(function(i){
		$(this).bind("mouseover",function(){
			 t = setTimeout(function(){
				conts.hide().eq(i).show();
				tabs.removeClass(currentClass).eq(i).addClass(currentClass);
			},200);
		}).bind("mouseout",function(){
			clearTimeout(t);
		});
	});
}
/*tab2*/
$.fn.TabADS2 = function(){
	var obj = $(this);
	var currentClass = "select";
	var tabs = obj.find(".tab_hd2").find("li");
	var conts = obj.find(".tab_cont2");
	var t;
	tabs.eq(0).addClass(currentClass);
	conts.hide();
	conts.eq(0).show();
	tabs.each(function(i){
		$(this).bind("click",function(){
			 conts.hide().eq(i).show();
			tabs.removeClass(currentClass).eq(i).addClass(currentClass);
		});
	});
}




/*邮箱手机验证*/
$.fn.TabADS4 = function(){
	var obj = $(this);
	var currentClass = "select";
	var tabs = obj.find(".tab_hd2").find("option");
	var conts = obj.find(".tab_cont2");
	var t;
	tabs.eq(0).addClass(currentClass);
	conts.hide();
	conts.eq(0).show();
	tabs.each(function(i){
		$(this).bind("click",function(){
			 conts.hide().eq(i).show();
			tabs.removeClass(currentClass).eq(i).addClass(currentClass);
		});
	});
}

/*tab3*/
$.fn.TabADS3 = function(){
	var obj = $(this);
	var currentClass = "select";
	var tabs = obj.find(".tab_hd").find(".radio");
	var conts = obj.find(".tab_cont");
	var t;
	tabs.eq(0).addClass(currentClass);
	conts.hide();
	conts.eq(0).show();
	tabs.each(function(i){
		$(this).bind("click",function(){
			 conts.hide().eq(i).show();
			tabs.removeClass(currentClass).eq(i).addClass(currentClass);
		});
	});
}
/*二级页分类列表*/
$.fn.Subsort = function(){
	var self = $(this);
	self.delegate('.click','click',function(event){
		$(this).parent().parent().toggleClass('open');
		event.preventDefault();
	});
}
/*点击区域小，修改后的二级页分类列表的第一级*/
$.fn.SubsortTop = function(){
	var self = $(this);
	self.delegate('.clicktop','click',function(event){
		$(this).parent().toggleClass('open');
		event.preventDefault();
	});
}
$.fn.Subsort2 = function(){
	var self = $(this);
	self.delegate('.click','click',function(event){
		$(this).parent().parent().toggleClass('open');
		event.preventDefault();
	});
}

/*查看更多搜索项*/
$.fn.showMore = function(){
	var self = $(this),txt='打开';
	var a = self.find('.other span');
	if(a.length < 10){self.next().find('.c_06c').hide();return;}
	a.filter(':gt(10)').addClass('hide');
	self.next().find('.c_06c').bind('click',function(event){
		txt = $(this)[0].className.indexOf('sq')>0?'更多':'收起';
		$(this).toggleClass('sq').text(txt);
		a.filter(':gt(10)').toggleClass('hide');
		event.preventDefault();
	});
}

/*详情页放大图片*/
function photoAlbum(node){
	var tab = node.find('.album_list'),
		tabs = tab.find('.item'),
		srcList = tabs.find('a'),
		bigPIC = node.find('#bigPIC'),
		imglink = '',
		superimgSRC = '',
		nowimglink = tab.find('.select').find('a').attr('href'),i = 0;
	var superPIC = $('#superPIC');
	tabs.hover(function(){
		i = tabs.index($(this));
		imglink = srcList.eq(i).attr('href');
		bigPIC.attr('src',imglink);
	},function(){
		
		bigPIC.attr('src',nowimglink);
	});
	tabs.bind('click',function(event){
		i = tabs.index($(this));
		imglink = srcList.eq(i).attr('href');
		superimgSRC = srcList.eq(i).attr('data-superbig');
		tabs.removeClass('select');
		$(this).addClass('select');
		bigPIC.attr('src',imglink);
		nowimglink = imglink;
		//超级大图
		superPIC.attr('src',superimgSRC);
		event.preventDefault();
	});
}
function superIMG(node){
	var img = node.find('#bigPIC'),
		mask = node.find('#img_mask'),
		po = jQuery('#po_bigView'),
		superIMG = po.find('#superPIC'),
		offset = node.offset(),
		offset_x = offset.left,
		offset_y = offset.top;
	var superSRC = img.attr('data-superbig');
	superIMG.attr('src',superSRC);
	node.mousemove(function(event){
		mask.css('display','block');
		po.show();
		var mousex = event.pageX - offset_x;
		var mousey = event.pageY - offset_y;
		var mask_x = mousex-75;
		var mask_y = mousey-75;
		if(mask_x<0)mask_x = 0;
		if(mask_y<0)mask_y = 0;
		if(mask_x+150 > 332)mask_x = 184;
		if(mask_y+150 > 332)mask_y = 184;
		mask.css({'left':mask_x,'top':mask_y});
		po.css({'top':offset_y,'left':offset_x+348});
		superIMG.css({'left':-mask_x*2,'top':-mask_y*2});
	});
	mask.mouseout(function(){
		mask.css('display','');
		po.hide();
	});
}

function pro_sel(node1,node2){
	var list = node1.find('.item');
	var bigPIC = $('#bigPIC');
	var superPIC = $('#superPIC');
	var imgSRC='',superimgSRC='';
	node1.delegate('.item','click',function(event){
		node2.val($(this).attr('data-value'));
		list.removeClass('select');
		$(this).addClass('select');
		//切换颜色改变大图
		if(node1.attr('id') == "sel_color"){
			var a = $(this).find('a')
			imgSRC = a.attr('href');
			superimgSRC = a.attr('data-superbig')
			bigPIC.attr('src',imgSRC);
			//超级大图
			superPIC.attr('src',superimgSRC);
		}
		event.preventDefault();
	});	
}

//返回顶部
$.fn.backTop = function(){
	var self = $(this);
	self.click(function() {
		$("html, body").animate({ scrollTop: 0 }, 120);
	});
}

/*订单详情*/
$.fn.OrderInfo = function(){
	var self = $(this);
	self.delegate('.item','hover',function(){
		$(this).parent().toggleClass("hover");
	});
}

/*新增修改收货地址、新增分类*/
$.fn.AddTab = function(){
	var self = $(this);
	var obj = self.find(".click");
	var tabs = self.find(".add_form");
	var clos = tabs.find(".close");
	obj.click(function(){
		tabs.removeClass("hide");
		tabs.addClass("open");
	});
	clos.click(function(){
		tabs.removeClass("open");
		tabs.addClass("hide");
	});
}

/*全选
classs:被选框class
selfid：全选框id
*/
function checkAll(classs,selfid){
	$('.'+classs).attr('checked',$('#'+selfid).attr('checked')?'checked':false);
}

/*
 * 全选
 * @param e 全选选择框名称
 * @param itemName 单个选择框名称
 * */
function CheckAll(e,itemName)
{
	var allSelect=false;
	var allSelect2=true;
	var bb = document.getElementsByName(e);
	var aa = document.getElementsByName(itemName);
	for (var i=0; i<bb.length; i++){
    	if(bb[i].checked)  
    		allSelect=true;
    }
	for (var i=0; i<aa.length; i++){
    	if(!aa[i].checked)  
    		allSelect2=false;
    }
	if(allSelect&&allSelect2){
		allSelect = false;
	}
    if(allSelect){
	    for (var i=0; i<aa.length; i++)
	        aa[i].checked = true;
	    for (var i=0; i<bb.length; i++)
	    	bb[i].checked = true;
    }else{
    	for (var i=0; i<aa.length; i++)
	        aa[i].checked = false;   
    	for (var i=0; i<bb.length; i++)
	    	bb[i].checked = false;
    }
}

/*侧导航*/
$.fn.Cur = function(){
	var self = $(this);
	var curName = 'cur';
	var obj = self.find(".item");
	obj.click(function(){
		obj.removeClass("cur");
		$(this).addClass("cur"); 
	});
}

/*分类管理*/
$.fn.TypeList = function(){
	var self = $(this);
	self.delegate('.ico_fold','click',function(event){
		$(this).parent().parent().parent().parent().toggleClass('open');
		event.preventDefault();
	});
}
/*宝贝归类*/
$.fn.ClassifyTab = function(){
	var self = $(this);
	self.delegate('.click','click',function(event){
		$(this).next().toggleClass('hide');
		$(this).parent().css('z-index','8');
		event.preventDefault();
	});
	self.delegate('.close','click',function(event){
		$(this).parent().toggleClass('hide');
		$(this).parent().parent().css('z-index','6');
		event.preventDefault();
	});
}
/*设置运费*/
$.fn.Fregihtset = function(){
	var self = $(this);
	self.delegate('.click','click',function(event){
		$(this).parent().prev().removeClass('hide');
		event.preventDefault();
	});
}
/*修改价格、库存*/
$.fn.Alterbox = function(){
	var self = $(this);
	self.delegate('.ico_edit','click',function(event){
		$(this).parent().addClass('showbox');
		event.preventDefault();
	});
	self.delegate('.ico_close','click',function(event){
		$(this).parent().parent().removeClass('showbox');
		event.preventDefault();
	});
}
/*发布产品属性名称更改*/
$.fn.Attribute = function(){
	var self = $(this);
	var txt = self.find('.labelname');
	var ipt = self.find('.editbox');
	txt.click(function(){
		$(this).parent().addClass('click');
		$(this).next('.editbox').focus();
		});
	ipt.blur(function(){
		$(this).parent().removeClass('click');
		});
	}
/*在线申请*/
$.fn.Apply = function(){
	var obj = $(this);
	var currentClass = "select";
	var tabs = obj.find(".form_list").find(".radio");
	var conts = obj.find(".license");
	var t;
	tabs.eq(1).addClass(currentClass);
	conts.hide();
	conts.eq(1).show();
	tabs.each(function(i){
		$(this).bind("click",function(){
			 conts.show().eq(i).hide();
			tabs.removeClass(currentClass).eq(i).addClass(currentClass);
		});
	});
}

/*
 * 单选
 * @param e 全选选择框名称
 * @param itemName 单个选择框名称
 * */
function CheckList(e,itemName)
{
    var aa = document.getElementsByName(itemName);
    var bb = document.getElementsByName(e);
    var allSelect=true;
    for (var i=0; i<aa.length; i++){
    	if(!aa[i].checked)  allSelect=false;
    }
    if(allSelect){
    	for (var i=0; i<bb.length; i++)
    		bb[i].checked = true;
    }else{
    	for (var i=0; i<bb.length; i++)
    		bb[i].checked = false;
    }  
}

/*倒计时*/
function countdown(id){
	var time = $('#'+id).text();
	var D = Math.floor(time/(3600*24));//计算天数
	var H = Math.floor((time%(3600*24))/3600);//小时
	var I = Math.floor(((time%(3600*24))%3600)/60);//分钟
	var S = Math.floor((((time%(3600*24))%3600)%60)/60);//秒
	$('#'+id).text(D+'天'+H+'时'+I+'分'+S+'秒');
	$('#'+id).attr('val',time);
	setInterval('countdownauto("'+id+'")',1000);
}

/*倒计时自动变化*/
function countdownauto(id){
var time = $('#'+id).attr('val');//alert(time);
time--;
var D = Math.floor(time/(3600*24));//计算天数
var H = Math.floor((time%(3600*24))/3600);//小时
var I = Math.floor(((time%(3600*24))%3600)/60);//分钟
var S = Math.floor((((time%(3600*24))%3600)%60)%60);//秒
$('#'+id).text(D+'天'+H+'时'+I+'分'+S+'秒');
$('#'+id).attr('val',time);
} 

/*手机发送验证码等待时间*/
var wait=60;
function time(o,p) {
        if (wait == 0) {
            o.removeAttr("disabled");  
            //$('#r_mob').removeAttr("disabled");
            //$('#Member_zm_email').removeAttr("disabled");
            //$('#Member_zm_mob').removeAttr("disabled");
            o.val("点击发送验证码");
            p.html("如果您在1分钟内没有收到验证码，请检查您填写的手机号码是否正确或重新发送");
            wait = 60;
        } else {
            o.attr("disabled", true);
            //$('#r_mob').attr("disabled", true);
            //$('#Member_zm_email').attr("disabled", true);
            //$('#Member_zm_mob').attr("disabled", true);
            o.val(wait + "秒后重新获取验证码");
            wait--;
            setTimeout(function() {
                time(o,p);
            },
            1000)
        }
}

function focusPlayerno(w,bd,time,hd){
		var $w=$(w),$c=$w.find(bd),l=$c.length,ol="<ol><li class='current'>&nbsp;</li>",t=time||6,t=t*1000;
		if(hd!=null){var $t=$w.find(hd);};
		if(l>1){for(i=2;i<=l;i++){ol+="<li>&nbsp;</li>";}};
		ol+="</ol>";
		$w.append(ol);
		var o=$w.find("ol li"),current;
		function play(i){
			$c.stop(true,true);
			$c.eq(i).fadeIn("normal").siblings(bd).fadeOut("fast");
			if(hd!=null){
				$t.stop(true,true);
				$t.eq(i).fadeIn("normal").siblings(hd).fadeOut("fast");
				};
			o.eq(i).addClass("current").siblings().removeClass("current");
			current = ++i;
			}
		(autoPlay=function(){
			   playIng = setInterval(function() {if (current == null) {current = 1;} else if (current ==l) {current = 0;}
			   play(current);
				   },t);
			})();
		o.each(function(i){$(this).hover(function(){clearInterval(playIng);play(i)},function(){autoPlay();});});	
	}	

function rePadding(f,t){

	$(f).each(function (){

		var $t=$(this).children(t),h=Math.round(($(this).height() - $t.height()) / 2);

		$t.css({

			paddingTop:h,

			paddingBottom:h

		});

	});

}

