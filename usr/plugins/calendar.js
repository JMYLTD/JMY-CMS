/*
* За разработку данного плагина говорим спасибо великому и ужасному(в хорошем смысле) RRRinat'у. Он мой онлайн друг, быдлокодер, мать тереза, дающая в долг и вообще вездесущий человек. А ещё хочу передать привет маме...
*/

function calendar(year, month, thisDay)
{
    if(year != null || month != null)
    {
        eval(AJAXEngine.notAsyncReq('ajax.php?do=calendar/' + year+','+month));
    }
    
	var year = (year		== null) ? new Date().getFullYear()	: year;
	var month = (month	== null) ? new Date().getMonth() : month;
	var exmonth	= new Date().getMonth();
	var thisDay = (thisDay	== null) ? new Date().getDate()	: thisDay;
	var oldyear	= year -1;
	var dayMonth = new Date(year, month+1, 0).getDate(); // Количество дней в месяце
	var dayCount = 1;
	var mark = new Array();
	var weekDays = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб']; // Недели. Для отладки
	var rusDays	= [6, 0, 1, 2, 3, 4, 5]; // Для русской разметки календаря
	var rusMonths =  ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
	var firstDay = new Date(year, month, 1).getDay(); // Первый день календаря
	var lastDay	= new Date(year, month,  dayMonth).getDay(); // Последний день календаря
	var mark = new Array();
	var beforeMonth	= rusDays[firstDay];
	var affterMonth	= (6- rusDays[lastDay]);
	var lastMonth = (month != 0) ? month -1 : 11;
	var nextMonth = (month != 11) ? month +1 : 0;
	var sumMarks = beforeMonth + dayMonth + affterMonth;
	var sumWeeks = parseInt(sumMarks / 7);
	var lastWeek = (sumWeeks-1);
	var mark = 1;
	var dd = new Array();
	var a = '';
	var b = '';
	var lastYear2 = null;
	var nextYear2 = null;
	var nowUnix = new Date().getTime();
	var timeUnix = new Date(year, month, thisDay).getTime();

	if (month == 0)
	{
		lastGenerate = (year-1) + ',' + '11';
		nextGenerate = year+', '+nextMonth;
	}
	else if(month == 11)
	{
		lastGenerate = year+', '+lastMonth;
		nextGenerate = (year+1)+','+'0';
	}
	else
	{
		lastGenerate = year+', '+lastMonth;
		nextGenerate = year+', '+nextMonth;
	}
	var c = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="_calendar">';
	c += '<tr><td colspan="7" align="center" style="padding-bottom:5px; padding-top:5px;"><div style="float:left"><a href="javascript:void(0);" onclick="calendar(' + lastGenerate + ');">&larr; '+rusMonths[lastMonth].toLowerCase()+'</a></div><div style="float:right"><a href="javascript:void(0);" onclick="calendar('+nextGenerate+');">'+rusMonths[nextMonth].toLowerCase()+' &rarr;</a></div></td></tr>';
	
	if(timeUnix > nowUnix)
		c += '<td colspan="7" align="center" style="padding-bottom:5px; padding-top:5px;">Далее ничего нового нет</td>';
	else if(newsDays.length == 0 && (year!=new Date().getFullYear() || month!=new Date().getMonth() || thisDay!=new Date().getDate()))
		c += '<tr><td colspan="7" align="center" style="padding-bottom:5px; padding-top:5px;">Новостей не найдено</td></tr>';
	c +='<tr align="center">';
	c += '<th class="day">ПН</th><th class="day"><b>ВТ</b></th><th class="day">СР</th><th class="day"><b>ЧТ</b></th><th class="day">ПТ</th><th class="holiday"><b>СБ</b></th><th class="holiday">ВС</th></tr>';
	c+='<tr  align="center" class="calendarTr">';
	workWeeks = sumWeeks+1;
	for (var i=1; i<workWeeks; i++)
	{
		c+='<tr  align="center" class="calendarTr">';
			for(j=0; j<7; j++)
			{
				if ((j < rusDays[firstDay] && i == 1) || (mark > dayMonth && i == workWeeks-1))
				{
					a += '<td class="rowEmpty">&nbsp;</td>';
				}
				else
				{
					if(!newsDays.in_array(mark))
					{
						addition = (mark == thisDay) ? 'dayNow' : '';
						kmark = (mark == thisDay && month == exmonth) ? ((j == 5 || j == 6) ? '<font color="red">'+mark+'</font>' : '<font color="blue">'+mark+'</font>') : mark;
						kmark1 = (mark == thisDay && month == exmonth) ? 'id="today"' : '';
						if (j == 5 || j == 6)	b+='<td '+kmark1+' class="isHolid calendarBorder ' + addition + '"><font color="red">'+kmark+'</font></td>';
						else		a+='<td '+kmark1+' class="calendarBorder ' + addition + '">'+kmark+'</td>';
					}
					else
					{
						if (j == 5 || j == 6)	b+='<td '+kmark1+' class="isNewsH"><a href="news/date/'+year+'-'+(month+1)+'-'+mark+'"><strong>'+mark+'</strong></a></td>';
						else		a+='<td '+kmark1+' class="isNews"><a href="news/date/'+year+'-'+(month+1)+'-'+mark+'"><strong>'+mark+'</strong></a></td>';
					}
					mark++;
				}
			}
		c+=a+b+'</tr>';
		a = '';
		b = '';
	}
	 c+=a+b+'</table>';
	 c+='<div style="padding-top:3px;" align="center">' + rusMonths[month] + '  | <a  href="javascript:void(0);" onclick="calendar('+(year -1)+', '+month+');">' + year + '</a> | <a href="javascript:void(0);" onclick="calendar();">Обнулить</a></div></div></div>';

	gid('_calendar').innerHTML = c;

}
