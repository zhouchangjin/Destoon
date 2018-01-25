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

$.fn.mytab = function(head,content){
	var obj = $(this);
	var currentClass = "select";
	var tabs = obj.find("."+head).find("li");
	var conts = obj.find("."+content);
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

function img_tip_ad(o, i) {
	if(i) {
		if(i.indexOf('nopic.gif') == -1) {
			if(i.indexOf('.thumb.') != -1) {var t = i.split('.thumb.');var s = t[0];} else {var s = i;}
			var aTag = o; var leftpos = toppos = 0;
			do {aTag = aTag.offsetParent; leftpos	+= aTag.offsetLeft; toppos += aTag.offsetTop;
			} while(aTag.offsetParent != null);
			var X = o.offsetLeft + leftpos + o.width+10;
			var Y = o.offsetTop + toppos - 40;
			Dd('img_tip').style.left = X + 'px';
			Dd('img_tip').style.top = Y + 'px';
			Ds('img_tip');
			Inner('img_tip', '<img src="'+s+'" onload="if(this.width<200) {Dh(\'img_tip\');}else if(this.width>300){this.width=300;}Dd(\'img_tip\').style.width=this.width+\'px\';"/>')
		}
	} else {
		Dh('img_tip');
	}
}

function A_Calendar(divid,options){
	var that=this;
	this.now=new Date();
	this.divid=divid;
	if(typeof options !='undefined'){
		this.options=options;
	}else{
		this.options={};
		
	}
	this.node=document.getElementById(this.divid);
	
	if(typeof this.options.height !='undefined'){
		this.height=this.options.height;
	}else{
		this.height=this.node.clientHeight;
	}
	
	if(typeof this.options.width !='undefined'){
		this.width=this.options.width;
	}else{
		this.width=this.node.clientWidth;
	}
	
	if(this.height<300){
		this.height=290;
	}
	
	if(this.width<180){
		this.width=250;
	}
	
	this.node.className+=" mCalendar";
	this.node.style.height=this.height+'px';
	this.node.style.width=this.width+'px';
	this.node.style.border="1px solid #e7e7e7";
	//this.node.style.boxShadow="1px 1px 1px #888888";
	this.node.style.background="rgb(255,255,241)";
	this.node.style.borderRadius="5px";
	
	this.dateTitleNode=document.createElement("div");
	this.dateTitleNode.className="mCalendar dateTitle";
	this.dateTitleNode.style.height=((this.height-20)/9)+"px";
	this.dateTitleNode.style.paddingLeft="20px";
	this.dateTitleNode.style.lineHeight=(this.height-20)/9+"px";
	this.dateTitleNode.innerHTML=""+new Date().format("yyyy年MM月dd日")+"&nbsp;&nbsp;"+new Date().getCWeekday();
	this.node.appendChild(this.dateTitleNode);
	
	this.cellWidth=(this.width-40)/7;
	this.cellHeight=(this.height-20)/9;
	
	this.calendarWrapper=document.createElement("div");
	this.calendarWrapper.style.height=((this.height-20)*8/9)+"px";
	this.calendarWrapper.style.width=(this.width-40)+"px";
	this.calendarWrapper.style.marginLeft=18+"px";
	this.calendarWrapper.style.background="#f8f8f8";
	this.calendarWrapper.style.textAlign="center";
	this.calendarWrapper.style.borderRadius="10px";
	this.calendarWrapper.style.boxShadow="1px 1px 1px #888888";
	this.node.appendChild(this.calendarWrapper);
	
	this.calendarHeader=document.createElement("div");
	this.calendarHeader.className="mCalendar calheader";
	this.calendarHeader.innerHTML="<ul><li style='width:"+this.cellWidth+"px;float:left;' >日</li><li style='width:"+this.cellWidth+"px;float:left;'>一</li><li style='width:"+this.cellWidth+"px;float:left;'>二</li><li style='width:"+this.cellWidth+"px;float:left;'>三</li><li style='width:"+this.cellWidth+"px;float:left;'>四</li><li style='width:"+this.cellWidth+"px;float:left;'>五</li><li style='width:"+this.cellWidth+"px;float:left;'>六</li></ul>";
	this.calendarHeader.style.height=(this.height-20)/9+"px";
	this.calendarWrapper.appendChild(this.calendarHeader);
	
	this.calendarBody=document.createElement("div");
	this.calendarWrapper.appendChild(this.calendarBody);
	var diff=this.now.getFirstDay();
	var firstDay=new Date(this.now.getTime());
	firstDay.setDate(1);
	var initialTime=firstDay.getTime()-diff*24*60*60*1000;
	for(var i=0;i<6;i++){
		var row=document.createElement("div");
		row.style.width="100%";
		for(var j=0;j<7;j++){
			var celldate=new Date(initialTime);
			initialTime+=24*60*60*1000;
			var cell=document.createElement("div");
			cell.style.width=(this.cellWidth-4)+"px";
			cell.style.height=(this.cellHeight-4)+"px";
			cell.style.float="left";
			cell.style.marginLeft="1px";
			cell.style.marginRight="1px";
			cell.style.marginTop='1px';
			cell.style.marginBottom='1px';
			cell.style.border="1px solid #e7e7e7";
			cell.style.cursor="pointer";
			
			if((i*7+j)%2==0){
				cell.style.background="#e7e7e1";
			}else{
				cell.style.background="#e1e7e7";
			}
			
			if(celldate.getMonth()==firstDay.getMonth()){
				if(celldate.getDate()!=this.now.getDate()){
					if(celldate.getDate()<this.now.getDate()){
						cell.style.color="#333";
					}else{
						cell.style.color="#fff";
						cell.style.fontWeight="bold";
						if(celldate.getDay()==0 || celldate.getDay()==6){
							cell.style.background="#0FAB64";
						}else{
							cell.style.background="#f85415";
						}
					}
				}else{
					cell.style.color="#fff";
					cell.style.background="#f85415";
					cell.style.fontWeight="bold";
				}
				
			}else{
				cell.style.color="#aaa";
			}
			if(j==6){
				cell.style.clear="right";
			}
			cell.innerHTML=""+celldate.getDate();
			row.appendChild(cell);
		}
		this.calendarBody.appendChild(row);
	}

}


function A_album(divid,options){
	var that=this;
	this.divid=divid;
	if(typeof options !='undefined'){
		this.options=options;
	}else{
		this.options={};
		
	}
	this.node=document.getElementById(this.divid);

	if(typeof this.options.height !='undefined'){
		this.height=this.options.height;
	}else{
		this.height=this.node.clientHeight;
	}
	
	if(typeof this.options.width !='undefined'){
		this.width=this.options.width;
	}else{
		this.width=this.node.clientWidth;
	}
	
	if(this.height<300){
		this.height=300;
	}
	
	this.node.className+=" mAlbum";
	this.node.style.height=this.height+'px';
	this.node.style.width=this.width+'px';
	this.imgWrapper=document.createElement("div");
	this.imgnode=document.createElement("img");
	this.slidernode=document.createElement("div");
	this.imgWrapper.appendChild(this.imgnode);

	this.node.appendChild(this.imgWrapper);
	
	this.node.appendChild(this.slidernode);
	this.node.style.background="rgb(29,46,62)";
	if(typeof this.options.imgs!='undefined'){
		this.imgs=this.options.imgs;
	}else{

	}
	
	this.imgnode.src=this.imgs[0].src;
	this.imgnode.width=this.width;
	this.imgnode.height=this.height;
	
	this.slidernode.style.height='65px';
	this.slidernode.style.width='100%';
	this.slidernode.style.position='relative';
	this.slidernode.style.bottom="75px";
	this.slidernode.style.background="rgba(29,46,62,0.6)";
	this.ulnode=document.createElement("ul");
	
	for(var i=0;i<this.imgs.length;i++){
		var lithumbi=document.createElement("li");
		lithumbi.style.float='left';
		var thumbi=document.createElement("img");
		thumbi.src=this.imgs[i].src;
		thumbi.width=115;
		thumbi.height=60;
		thumbi.onclick=function(){
			var tmp=this;
			that.select(tmp);
		}
		lithumbi.appendChild(thumbi);
		lithumbi.style.marginRight="3px";
		lithumbi.style.cursor="pointer";
		if(i==0){
			thumbi.className="select";
		}
		this.ulnode.appendChild(lithumbi);
	}
	this.slidernode.appendChild(this.ulnode);
	var selectorNode=document.createElement("div");
	selectorNode.className="selector";
	this.slidernode.appendChild(selectorNode);
	selectorNode.style.position='absolute';
	this.selectorNode=selectorNode;
	
	this.select=function(obj){
		var imgnodes=that.ulnode.getElementsByTagName("img");
		for(var i=0;i<imgnodes.length;i++){
			imgnodes[i].className="";
		}
		obj.className="select";
		that.selectorNode.style.left=obj.offsetLeft+"px";
		this.imgnode.src=obj.src;
	}

}

Date.prototype.format = function(fmt) { 
    var o = { 
       "M+" : this.getMonth()+1,                 //月份 
       "d+" : this.getDate(),                    //日 
       "h+" : this.getHours(),                   //小时 
       "m+" : this.getMinutes(),                 //分 
       "s+" : this.getSeconds(),                 //秒 
       "q+" : Math.floor((this.getMonth()+3)/3), //季度 
       "S"  : this.getMilliseconds()             //毫秒 
   }; 
   if(/(y+)/.test(fmt)) {
           fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
   }
    for(var k in o) {
       if(new RegExp("("+ k +")").test(fmt)){
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
        }
    }
   return fmt; 
}       

Date.prototype.getCWeekday=function(){
	var weekday=new Array(7)
	weekday[0]="星期日";
	weekday[1]="星期一";
	weekday[2]="星期二";
	weekday[3]="星期三";
	weekday[4]="星期四";
	weekday[5]="星期五";
	weekday[6]="星期六";
	 return weekday[this.getDay()];
}

Date.prototype.getFirstDay=function(){
	var d=new Date(this.getTime());
	d.setDate(1);
	return parseInt(d.getDay());
}
