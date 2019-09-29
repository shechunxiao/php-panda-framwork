//滑动更新时间
$(document).on("pageInit", "#account", function (e, id, page) {
    $('#account .content').scrollTop(0);
    $('#account .content').scroll(function (event) {
        var perIndex;
        var dom;
        $('#account .banner_tops').each(function (index) {
            var thisTop = $(this).position().top - $('.payLogRefresh').eq(0).height();
            if (thisTop > 0) {
                dom = $('.banner_tops').eq(perIndex);
                var text = dom.text();
                var normalText = $('.data_times').text();
                if (text == '') {
                    $('.data_times').text(normalText);
                } else {
                    $('.data_times').text(text);
                }
            } else if (index == $('.banner_tops').length - 1) {
                dom = $('.banner_tops').eq(index);
                var text = dom.text();
                var normalText = $('.data_times').text();
                if (text == '') {
                    $('.data_times').text(normalText);
                } else {
                    $('.data_times').text(text);
                }
            } else {
                perIndex = index;
            }
        })

        var acc_scroll = $('#account .content')[0].scrollTop;
        obj = JSON.stringify(acc_scroll);
        localStorage.setItem("对账统计", obj);

    });

    //获取时间
    function GetDateStr(AddDayCount) {
        var dd = new Date();
        dd.setDate(dd.getDate() + AddDayCount);//获取AddDayCount天后的日期
        var y = dd.getFullYear();
        var m = dd.getMonth() + 1;//获取当前月份的日期
        var d = dd.getDate();
        if (m < 10) {
            m = "0" + m;
        }
        if (d < 10) {
            d = "0" + d;
        }
        return y + "-" + m + "-" + d;
    }

    //console.log(GetDateStr(0));
    //console.log($('.data_times').text());
    if (GetDateStr(0) == $('.data_times').text()) {
        $('.data_times').text('今天')
        //$('.vipList .banner_tops').eq(0).text('今天')
    }
    if ($('.vipList .banner_tops').eq(1).text() == GetDateStr(-1)) {
        $('.vipList .banner_tops').eq(1).text('昨天')
    }
    if ($('.newTime').val() == $('.oldTime').val()) {
        $('.zhi').hide()
        $('.oldTime').hide()
    } else {
        $('.zhi').show()
        $('.oldTime').show()
    }
    var oo = localStorage.getItem('src_acc');
    if (oo == 'TotalRecharge' || oo == 'UnitPrice' || oo == 'TotalRevenue' || oo == 'allsearch') {
        var odd = JSON.parse(localStorage.getItem("对账统计"));
        $('#account .content').scrollTop(odd)
        localStorage.removeItem('src_acc')
    }
    var val = $('.checka').find('.green').attr('status');
    if (val == 1) {
        $('#account .data_times').hide();
        $('#account .content').css({top: '73px'})
    }

    var lisLength = $('.shadowBox').length;
    if (lisLength < 10 && lisLength > 0) {
        $('.poyLogBottom').css('display', 'block');
    }

    clearBorderTop();
})

function clearBorderTop() {
    for (var i = 0; i < $('#account .shadowBox').length; i++) {
        $('#account .shadowBox').eq(i).find('#account .chiBox').first().css('border', '0');
    }
}

//打印日结小票
$(document).on('click', '#account .print', function () {
    $.confirm('打印内容超过100条时<br/>建议登录电脑端导出所需数据', '温馨提示',
        function () {

        },
        function () {
            $.showPreloader();
            var start = $('.newTime').val();
            if (start == undefined) {
                start = ''
            }
            var end = $('.oldTime').val();
            if (end == undefined) {
                end = ''
            }

            var val = $('.checka').find('.green').attr('status');
            var mctId = $('#viewBu').val()
            var shopkeeper = $('#viewBu').attr('value2');
            var merchantname = $('.viewBu').text()
            var merchantidIn = $('#viewBu').attr('value3')
            var Html = '';
            $.ajax({
                url: '/group=waiterapp&action=payment&method=checkTicket?branch=display&firstTime=' + start + '&endTime=' + end + '&val=' + val + '&mctId=' + mctId,
                methold: "POST",
                data: {},
                success: function (data) {
                    // console.log(data);
                    $.hidePreloader();
                    print(Html, data.ticket, data.count, data.laststoreId, data.time, data.businessMemberName, data.fication, data.branchName,data.merchantType,data.val, data)
                    if ($('#checkCloses .ticket').html() != '') {
                        $('#shows').show();
                        $('.fullpagePayFour').show();
                    }
                },
                error: function () {
                    console.log("请求失败");
                }
            })
        }
    );
    $('.modal-button').eq(0).html("仍要打印");
    $('.modal-button').eq(0).css('color', '#666');
    $('.modal-button').eq(1).html("取消");
    $('.modal-button').eq(1).css('color', '#21c17b');
})
$(document).on('click', '#account #checkCloses', function () {
    $('#shows').hide();
    $('.fullpagePayFour').hide();
    $('#checkTick .ticket').html('')
    $('#checkSaveTicket .ticket').html('')
})
$(document).on('click', '#account .timeBB', function (e) {
    e.stopPropagation()
    e.cancelBubble = true;
    $('#customs').show();
    $('.custom-cont_fullpagePay').show();
})
$(document).on('click', '#account .changeBusiness', function (e) {
    e.stopPropagation()
    e.cancelBubble = true;
    $(this).parent().css('z-index', 99999)
    $('.businessList').show()
    $('.custom-cont_fullpagePay').show();
})
// 切换商家
$(document).on('click', '#account .changeBusiness p', function () {
    var start = $('.newTime').val();
    var end = $('.oldTime').val();
    var mctId = $(this).data('action');
    if (start == 'undefined' || start == undefined) {
        start = ''
    }
    if (end == 'undefined' || end == undefined) {
        end = ''
    }
    //获取点击单日汇总
    var val = $('.checka').find('.green').attr('status');
    var merchantidIn = $(this).attr('data-action')
    var merchantname = $(this).attr('data-few')
    window.location.href = '/group=waiterapp&action=payment&method=check?mctId=' + mctId + '&firstTime=' + start + '&endTime=' + end + '&val=' + val;

})
$(document).on('click', '#account .confirmLeft', function () {
    $('#customs').hide();
    $('.custom-cont_fullpagePay').hide();
    $('.weui-picker').hide()
})
$(document).on('click', '#account .begin_time', function () {
    $('.weui-picker').show()
})
$(document).on('click', '#account .end_time', function () {
    $('.weui-picker').show()
})
$(document).on('click', '.custom-cont_fullpagePay', function () {
    $('#customs').hide();
    $('.custom-cont_fullpagePay').hide();
    $('.weui-picker').hide();
    $('.customBox').hide();
    $('.custom_lis>li>span>i').removeClass('icon-chenggong1 green');
    $('.custom_lis>li>span>i').attr('status', '0')
    $('.businessList').hide()
    $('.data_box .datas_account').css('z-index', 100)
})
$(document).on('click', '.fullpagePayFour', function () {
    $('#shows').hide();
    $('.fullpagePayFour').hide();
    $('#checkTick .ticket').html('')
    $('#checkSaveTicket .ticket').html('')
})
//时间选择器单日汇总点击
//单日汇总封装
function dayAllday(succOne, succTwo) {
    if ($(succOne).hasClass('green')) {
        $(succOne).removeClass('green');
        $(succTwo).addClass('green')
    } else {
        $(succOne).addClass('green');
        $(succTwo).removeClass('green')
    }
}

$(document).on('click', '.check_checkeda', function () {
    dayAllday('.succOne', '.succTwo')
})
$(document).on('click', '.check_checkedb', function () {
    dayAllday('.succTwo', '.succOne')
})
var allData_sureLink = true
$(document).on('click', '#allData_sure', function () {
    function GetDateStr(AddDayCount) {
        var dd = new Date();
        dd.setDate(dd.getDate() + AddDayCount);//获取AddDayCount天后的日期
        var y = dd.getFullYear();
        var m = dd.getMonth() + 1;//获取当前月份的日期
        var d = dd.getDate();
        return y + "-" + m + "-" + d;
    }

    $('.weui-picker').hide();
    var start = $('#beginTime_picker').val().split('').slice(0, 10).join('');
    var end = $('#endTime_picker').val().split('').slice(0, 10).join('');
    // console.log(start)
    // console.log(end)
    //获取点击单日汇总
    var val = $('.checka').find('.green').attr('status');
    var starts = new Date(start.replace("-", "/").replace("-", "/"));
    var ends = new Date(end.replace("-", "/").replace("-", "/"));
    var mctId = $('#viewBu').val()
    var shopkeeper = $('#viewBu').attr('value2');
    var merchantname = $('.viewBu').text()
    var merchantidIn = $('#viewBu').attr('value3')
    // console.log(mctId);
    // console.log(shopkeeper)
    // console.log(GetDateStr(0));
    if (starts > ends) {
        $('#checkId').show();
        setTimeout("$('#checkId').hide()", 2000)
    } else if (starts == '' || end == '') {
        $('#checkIdTwo').show();
        setTimeout("$('#checkIdTwo').hide()", 2000)
    } else {
        if (allData_sureLink) {
            //请求接口
            allData_sureLink = false
            window.location.href = '/group=waiterapp&action=Payment&method=check?firstTime=' + start + '&endTime=' + end + '&val=' + val + '&mctId=' + mctId;
        }
    }
});

//下拉上滑拼接
function accountStatistics(Html, data, upOrDown) {
    function GetDateStr(AddDayCount) {
        var dd = new Date();
        dd.setDate(dd.getDate() + AddDayCount);//获取AddDayCount天后的日期
        var y = dd.getFullYear();
        var m = dd.getMonth() + 1;//获取当前月份的日期
        var d = dd.getDate();
        if (m < 10) {
            m = "0" + m;
        }
        if (d < 10) {
            d = "0" + d;
        }
        return y + "-" + m + "-" + d;
    }
    var lists = data.data;
    var count = data.count;
    $.each(lists, function (index, val) {
        if (count.time === index){
            if (index == GetDateStr(0)) {
                Html += '<div class="banner_tops payLogfristlist">今天</div>'
            } else if (index == GetDateStr(-1)) {
                Html += '<div class="banner_tops payLogfristlist">昨天</div>'
            } else {
                Html += '<div class="banner_tops payLogfristlist">' + index + '</div>'
            }
        }else{
            if (index == GetDateStr(0)) {
                Html += '<div class="banner_tops">今天</div>'
            } else if (index == GetDateStr(-1)) {
                Html += '<div class="banner_tops">昨天</div>'
            } else {
                Html += '<div class="banner_tops">' + index + '</div>'
            }
        }
        $.each(val, function (i, item) {
            Html += '<div class="shadowBox" stat="' + item.bankCheck + '" data_times="' + index + '"  stor="' + item.storeId + '" mct="' + item.mctId + '">';
            if (item.AWmoney !=='' && item.AWmoney !== null && item.AWmoney !== 0 && item.AWmoney !== '0'){
                Html += '<div class="titCard">';
                Html += '<div>';
                Html += '会员卡';
                Html += '</div>';
                Html += '</div>';

                Html += '<div class="bankBox cneter_topsss"  data_times="' + index + '" stor="' + item.storeId + '" mct="' + item.mctId + '">';
                Html += '<p class="pOne">银行卡应到账合计: <span class="orange">￥ ' + item.AWmoney + '</span></p>';
                if (item.bankMemberName !== ''){
                    Html += '<p class="pTwo">银行卡号: ' + item.numberCard + '(' + item.bankMemberName + ')</p>';
                }else{
                    Html += '<p class="pTwo">银行卡号: ' + item.numberCard + '</p>';
                }
                Html += '</div>';
                //微信
                Html += '<div class="chiBox WeChatsss"   data_times="' + index + '"  stor="' + item.storeId + '" mct="' + item.mctId + '">';
                Html += '<div class="icon"><i class="iconfont icon-weixinzhifu1-copy"></i></div>';
                Html += '<div class="detailss">';
                Html += '<span class="orange" style="margin: 0">￥ ' + item.wechatmoney + '</span>';
                Html += '<span>手续费:<a class="orange"> ￥ ' + item.wechatRate + '</a></span>';
                Html += '</div>';
                Html += '</div>';
                //支付宝
                Html += '<div class="chiBox Alipaysss"   data_times="' + index + '"  stor="' + item.storeId + '" mct="' + item.mctId + '">';
                Html += '<div class="icon"><i class="iconfont icon-zhifubao"></i></div>';
                Html += '<div class="detailss">';
                Html += '<span class="orange" style="margin: 0">￥ ' + item.alipaymoney + '</span>';
                Html += '<span>手续费: <a class="orange">￥ ' + item.alipayRate + '</a></span>';
                Html += '</div>';
                Html += '</div>';
            }
            if (item.mctAWmoney !=='' && item.mctAWmoney !== null && item.mctAWmoney !==0 && item.mctAWmoney !=='0'){
                Html += '<div class="nowMoney chiBox" >';
                Html += '<div class="moneysDiv" style="line-height: 25px;">总店银行卡应到账合计: <span class="orange">￥ ' + item.mctAWmoney + '</span></div>';
                Html += '</div>';
                //微信
                Html += '<div class="chiBox WeChatsss"   data_times="' + index + '"  stor="' + item.storeId + '" mct="' + item.mctId + '">';
                Html += '<div class="icon"><i class="iconfont icon-weixinzhifu1-copy"></i></div>';
                Html += '<div class="detailss">';
                Html += '<span class="orange" style="margin: 0">￥ ' + item.mctWechatmoney + '</span>';
                Html += '<span>手续费: <a class="orange">￥ ' + item.mctWechatRate + '</a></span>';
                Html += '</div>';
                Html += '</div>';
                //支付宝
                Html += '<div class="chiBox Alipaysss"   data_times="' + index + '"  stor="' + item.storeId + '" mct="' + item.mctId + '">';
                Html += '<div class="icon"><i class="iconfont icon-zhifubao"></i></div>';
                Html += '<div class="detailss">';
                Html += '<span class="orange" style="margin: 0">￥ ' + item.mctAlipaymoney + '</span>';
                Html += '<span>手续费: <a class="orange">￥ ' + item.mctAlipayRate + '</a></span>';
                Html += '</div>';
                Html += '</div>';
                Html += '</div>';
            }
            //一卡通收款到本店
            if (item.xAWmoney !=='' && item.xAWmoney !== null && item.xAWmoney !==0 && item.xAWmoney !=='0'){
                Html += '<p style="width: 100%;height: 1rem;background: rgb(251,251,251);margin-top: .5rem;margin-bottom: .5rem;"></p>';
                Html += '<div class="titCard">';
                Html += '<div>一卡通</div>';
                Html += '<div id="detailsUrl" data-accounttype="' + item.branchName + '" data-single="' + item.xwechatRate + '" data-singlefirst="' + item.xfirstWechatRate + '" data-sum="' + item.xmctWechatRate + '" data-sumfirst="' + item.xmctFirstWechatRate + '">详情</div>\n';
                Html += '</div>';
                Html += '<div class="bankBox cneter_topsss"  data_times="' + index + '" stor="' + item.storeId + '" mct="' + item.mctId + '">';
                Html += '<p class="pOne">银行卡应到账合计: <span class="orange">￥ ' + item.xAWmoney + '</span></p>';
                if (item.bankMemberName !== ''){
                    Html += '<p class="pTwo">银行卡号: ' + item.numberCard + '(' + item.bankMemberName + ')</p>';
                }else{
                    Html += '<p class="pTwo">银行卡号: ' + item.numberCard + '</p>';
                }
                Html += '</div>';
                //微信
                Html += '<div class="chiBox WeChatsss"   data_times="' + index + '"  stor="' + item.storeId + '" mct="' + item.mctId + '">';
                Html += '<div class="icon"><i class="iconfont icon-weixinzhifu1-copy"></i></div>';
                Html += '<div class="detailss">';
                Html += '<span class="orange" style="margin: 0">￥ ' + item.xwechatmoney + '</span>';
                Html += '</div>';
                Html += '</div>';
                Html += '</div>';
            }
            //一卡通收款到总店
            if (item.xmctAWmoney !=='' && item.xmctAWmoney !== null && item.xmctAWmoney !==0 && item.xmctAWmoney !=='0'){
                Html += '<div id="detailsUrl" data-accounttype="' + item.branchName + '" data-single="' + item.xmctWechatRate + '" data-singlefirst="' + item.xmctFirstWechatRate + '" data-sum="' + item.xmctWechatRate + '" data-sumfirst="' + item.xmctFirstWechatRate + '">详情</div>\n';
                Html += '<div class="nowMoney chiBox" >';
                Html += '<div class="moneysDiv" style="line-height: 25px;">总店银行卡应到账合计: <span class="orange">￥ "' + item.xmctAWmoney + '"</span></div>';
                Html += '</div>';
                //微信
                Html += '<div class="chiBox WeChatsss"   data_times="' + index + '"  stor="' + item.storeId + '" mct="' + item.mctId + '">';
                Html += '<div class="icon"><i class="iconfont icon-weixinzhifu1-copy"></i></div>';
                Html += '<div class="detailss">';
                Html += '<span class="orange" style="margin: 0">￥ "' + item.xmctWechatmoney + '"</span>';
                Html += '</div>';
                Html += '</div>';
                Html += '</div>';
            }
            Html += '</div>';
        });
    })
    //在原有的之前加入刷新的
    if (upOrDown == 1) {
        $('#account').find('.vipList').html(Html);
        //$('.firstWeek').text(data[0].week);
        $('.vipList .banner_tops').first().hide();
    } else if (upOrDown == 2) {
        $('#account').find('.vipList').append(Html);
    }
}

//下拉刷新
// 添加'refresh'监听器
$(document).on('refresh', '#account .pull-to-refresh-content', function (e) {
    //$('#account .vipList .banner_tops').first().show();
    // var start = $('#beginTime_picker').val();
    // var end = $('#endTime_picker').val();
    var start = $('#beginTime_picker').val().split('').slice(0, 10).join('');
    var end = $('#endTime_picker').val().split('').slice(0, 10).join('');
    var statsO = $('.newTime').attr('newVal');
    var endsO = $('#hidEnd').val();
    if (statsO == '' || statsO == undefined || statsO == null || statsO == 'undefined') {
        start = '';
    }
    if (endsO == '' || endsO == undefined || endsO == null || endsO == 'undefined') {
        end = '';
    }
    var mctId = $('#viewBu').val()
    // var shopkeeper = $('#viewBu').attr('value2');
    // var merchantname = $('.viewBu').text()
    // var merchantidIn = $('#viewBu').attr('value3')
    //获取点击单日汇总
    var val = $('.checka').find('.green').attr('status');
    var Html = '';
    // 模拟2s的加载过程
    setTimeout(function () {
        if (val == 1) {
            // 加载完毕需要重置
            //$('.banner_tops').first().hide();
            $.pullToRefreshDone('.pull-to-refresh-content');
            return false;
        } else {
            $.ajax({
                'url': '/group=waiterapp&action=payment&method=checkdown?firstTime=' + start + '&endTime=' + end + '&val=' + val + '&mctId=' + mctId,
                'method': 'POST',
                data: {},
                success: function (data) {
                    console.log('shuju',data);
                    var upOrDown = 1;
                    accountStatistics(Html, data, upOrDown)
                    clearBorderTop();
                    $('#account .content').attr('data-offset', 0);
                    //$('.vipList .banner_tops').first().hide();
                },
                error: function (data) {
                    console.log("请求失败");
                }
            })
        }
        // 加载完毕需要重置
        $.pullToRefreshDone('.pull-to-refresh-content');
        //$('#account .vipList .banner_tops').first().show();
    }, 1000);


});
//无限滚动
$(document).on("pageInit", '#account', function (e, id, page) {
    var loading = false;
    // 每次加载添加多少条目
    var itemsPerLoad = 10;
    // 最多可加载的条目
    var maxItems = 1000;
    var lastIndex = $('#account .shadowBox').length;
    if (lastIndex >= 10) {
        $('.bottomPreloader').show()
    } else {
        $('.bottomPreloader').hide()
    }

    function addItemsCheck(number, lastIndex) {
        var lastIndex = $('#account .shadowBox').length;
        var statsO = $('.newTime').attr('newVal');
        var endsO = $('#hidEnd').val();
        var mctId = $('#viewBu').val()
        var start = $('#beginTime_picker').val().split('').slice(0, 10).join('');
        var end = $('#endTime_picker').val().split('').slice(0, 10).join('');
        var shopkeeper = $('#viewBu').attr('value2');
        var merchantname = $('.viewBu').text()
        var merchantidIn = $('#viewBu').attr('value3')
        if (statsO == '' || statsO == undefined || statsO == null || statsO == 'undefined') {
            start = '';
        }
        if (endsO == '' || endsO == undefined || endsO == null || endsO == 'undefined') {
            end = '';
        }
        //获取点击单日汇总
        var val = $('.checka').find('.green').attr('status');
        var offset = parseInt($(page).find('#account .content').attr('data-offset'));
        offset = (offset + 1);
        $('#account .content').attr('data-offset', offset);
        var Html = '';
        var isGo = true
        if (val == 1) {
            return false;
        } else {
            $.ajax({
                'url': '/group=waiterapp&action=Payment&method=checkAjax?firstTime=' + start + '&endTime=' + end + '&val=' + val + '&offset=' + offset + '&mctId=' + mctId,
                'dataType': 'json',
                'method': 'POST',
                'data': {},
                'async': false,
                'success': function (data) {
                    var upOrDown = 2;

                    if (data.length == 0) {

                        // 加载完毕，则注销无限加载事件，以防不必要的加载
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        // 删除加载提示符
                        $('.infinite-scroll-preloader').remove();
                        $('#account').find('.poyLogBottom').css('display', 'block');
                    } else {

                        accountStatistics(Html, data, upOrDown)
                        clearBorderTop()
                        $('#account').find('.poyLogBottom').css('display', 'none');
                    }

                }
            })
        }
    }

    // 注册'infinite'事件处理函数
    $(page).on('infinite', function () {
        // 如果正在加载，则退出
        if (loading) return;
        // 设置flag
        loading = true;
        // 模拟1s的加载过程
        setTimeout(function () {
            loading = false;
            if (lastIndex >= maxItems) {
                // 加载完毕，则注销无限加载事件，以防不必要的加载
                $.detachInfiniteScroll($('.infinite-scroll'));
                // 删除加载提示符
                $('.infinite-scroll-preloader').remove();
                $('.poyLogBottom').show();
                return;
            }
            addItemsCheck(itemsPerLoad, lastIndex);
            // 更新最后加载的序号
            lastIndex = $('#account .shadowBox').length;
            $.refreshScroller();
        }, 1000);
    });
})

//关闭详情
$(document).on('click', '.showInfo', function () {
    $('.showInfo').css('display','none');
})

// 点击详情
$(document).on('click', '#detailsUrl', function () {
    var accounttype = $(this).data('accounttype');
    var single = $(this).data('single');
    var singlefirst = $(this).data('singlefirst');
    var sum = $(this).data('sum');
    var sumfirst = $(this).data('sumfirst');
    console.log('account:',accounttype)
    console.log('single:',single)
    console.log('singlefirst',singlefirst)
    console.log('sum',sum)
    console.log('sumfirst',sumfirst)
    if (accounttype !== '' && accounttype !== '总店'){
        //总店详情
        var htmlSum = '';
        htmlSum += '<div class="info">';
        htmlSum += '<div class="it">';
        htmlSum += '<div>服务费（总店）:<span>￥ '+ sum + '</span></div>';
        htmlSum += '<div>(首单折扣:<span>￥ '+sumfirst+'</span>)</div>';
        htmlSum += '</div>';
        htmlSum += '<div class="it">';
        htmlSum += '<div>服务费（本店）:<span>￥ '+ single +'</span></div>';
        htmlSum += '<div>(首单折扣:<span>￥ '+ singlefirst +'</span>)</div>';
        htmlSum += '</div>';
        htmlSum += '</div>';
        $('.showInfo').html(htmlSum).css('display','block');
    }else{
        //单店详情
        var html = '';
        html += '<div class="info">';
        html += '<div class="it">';
        html += '<div>服务费:<span>￥ '+single+'</span></div>';
        html += '<div>(首单折扣:<span>￥ '+ singlefirst +'</span>)</div>';
        html += '</div>';
        html += '</div>';
        $('.showInfo').html(html).css('display','block');
    }
})

//打印小票拼接
// print(Html, data.ticket, data.count, data.laststoreId, data.time, data.businessMemberName, data.fication, data.branchName,data.merchantType,data.val)
function print(Html, data, count, last, time, businessMemberName, fication, namess,merchantType,val, dataPack) {
    console.log('数据',data)
    // console.log(dataPack)
    var firstTime = dataPack.firstTime
    var endTime = dataPack.endTime
    // console.log(firstTime == null)
    // console.log(endTime == null)
    $.each(data, function (x, vvv) {
        // console.log(x);
        // console.log(vvv);
        Html+='<div class="ckekhr"><hr></div>'
        Html+='<div class="checkName">' +
            '<span class="checkNameTop">日结小票</span>'
        if( merchantType == 'SINGLE' ){
            Html+='<span class="checkNameBottom">'+namess+'</span>'
        }
        Html+='</div>'
        Html+='<dl>'
        if(val == 0 ){
            Html+='<dd class="data_ams">日期：'+x+'</dd>'
        }else{
            if (firstTime != null && endTime != null) {
                if (firstTime == endTime ) {
                    Html+='<dd class="data_ams" 1>日期：'+firstTime+'</dd>'
                } else {
                    Html+='<dd class="data_ams" 2>日期：'+firstTime+' 至 '+endTime+'</dd>'
                }
            } else {
                Html+='<dd class="data_ams" 3>日期：'+x+'</dd>'
            }
        }
        Html+='<dd class="data_ams">操作人 : '+businessMemberName+'</dd>'+
            '</dl>';
        $.each(vvv,function (t, demo){
            Html+='<div>'
            Html+='<dl>'

            if( demo.branchName != '' && demo.type != 'xcard' ){
                if(demo.storeId != 0 && demo.storeId != '0') {
                    Html+='<span class="checkNameBottom" style="text-align: left;" 1>门店名称：</span>'
                }
                Html+='<span class="checkNameBottom" 2>'+demo.branchName+':</span>'
            }
            Html+='</dl>'
            if (demo.type == 'xcard') {
                Html+='<div class="type" style="margin-top: .5rem;margin-bottom: .5rem;font-size: .65rem;">--- 一卡通 ---</div>'
            } else {
                Html+='<div class="type" style="margin-top: .5rem;margin-bottom: .5rem;font-size: .65rem;">--- 会员卡 ---</div>'
            }
            Html+='<div class="checkPayment">'
            Html+='<dl>'

            if( demo.storeId == '0' || demo.storeId == 0 ){
                Html+='<dd>银行卡应到账合计 : </dd>'
                Html+='<dd class="checkPaymentBottom">￥ '+demo.AWmoney+'</dd>'
                if(demo.bankMemberName != '') {
                    Html+='<span class="number_card_a">银行卡号：'+demo.numberCard+demo.bankMemberName+'</span>'
                } else {
                    Html+='<span class="number_card_a">银行卡号：'+demo.numberCard+'</span>'
                }
                if (demo.type == 'xcard') {
                    Html+='<dl>' +
                        '<dd>微信支付合计 :￥ '+demo.wechatmoney+'</dd>'+
                        '<dd class="checkPaymentBottom">服务费 : ￥ '+demo.wechatRate+'</dd>'+
                        '</dl>'
                }
                if (demo.type == 'card') {
                    Html+='<dl>' +
                        '<dd>微信支付合计 :￥ '+demo.wechatmoney+'</dd>'+
                        '<dd class="checkPaymentBottom">手续费 : ￥ '+demo.wechatRate+'</dd>'+
                        '</dl>'
                    Html+='<dl>' +
                        '<dd>支付宝合计 :￥ '+demo.alipaymoney+'</dd>'+
                        '<dd class="checkPaymentBottom">手续费 : ￥ '+demo.alipayRate+'</dd>'+
                        '</dl>'
                }
            }else{
                Html+='<dd>总店应到账合计 : </dd>'
                Html+='<dd class="checkPaymentBottom">￥ '+demo.mctAWmoney+'</dd>'
                if (demo.type == 'xcard') {
                    Html+='<dl>' +
                        '<dd>微信支付合计 :￥ '+demo.mctWechatmoney+'</dd>'+
                        '<dd class="checkPaymentBottom">服务费 : ￥ '+demo.mctWechatRate+'</dd>'+
                        '</dl>'
                }
                if (demo.type == 'card') {
                    Html+='<dl>' +
                        '<dd>微信支付合计 :￥ '+demo.mctWechatmoney+'</dd>'+
                        '<dd class="checkPaymentBottom">手续费 : ￥ '+demo.mctWechatRate+'</dd>'+
                        '</dl>'
                    Html+='<dl>' +
                        '<dd>支付宝合计 :￥ '+demo.mctAlipaymoney+'</dd>'+
                        '<dd class="checkPaymentBottom">手续费 : ￥ '+demo.mctAlipayRate+'</dd>'+
                        '</dl>'
                }

            }
            Html+='</dl>';
            if (demo.storeId != 0 && (demo.AWmoney !=0 || demo.mctAWmoney !=0)) {
                Html+='<dd>银行卡应到账合计 : </dd>'
                Html+='<dd class="checkPaymentBottom">￥ '+demo.AWmoney+'</dd>'
                if (demo.type == 'xcard') {
                    Html+='<dl>' +
                        '<dd>微信支付合计 :￥ '+demo.wechatmoney+'</dd>'+
                        '<dd class="checkPaymentBottom">服务费 : ￥ '+demo.wechatRate+'</dd>'+
                        '</dl>'
                }
                if (demo.type == 'card') {
                    Html+='<dl>' +
                        '<dd>微信支付合计 :￥ '+demo.wechatmoney+'</dd>'+
                        '<dd class="checkPaymentBottom">手续费 : ￥ '+demo.wechatRate+'</dd>'+
                        '</dl>'
                    Html+='<dl>' +
                        '<dd>支付宝合计 :￥ '+demo.alipaymoney+'</dd>'+
                        '<dd class="checkPaymentBottom">手续费 : ￥ '+demo.alipayRate+'</dd>'+
                        '</dl>'
                }
            }
            if ((demo[t+1]  && demo.storeId  != demo[t+1].storeId) || t==length-1) {
                Html+='<div style="overflow: hidden">**************************************************************************************************************************************************************************************************************************************************************************************************************************************</div>'
            }
        })
        Html += '<div>' +
            '<dl><hr/></dl>' +
            '<dl style="margin-top: .5rem">' +
            '<dd class="checkdata">打印日期：<span class="date">' + time + '</span></dd>' +
            '<dd class="checkwowo" style="margin-top: .3rem!important;">蜗蜗智慧门店提供技术支持</dd>' +
            '<dl><hr/></dl>'
        Html += '</dl>'
        Html += '</div>'
        Html += '</div>'
    })

    $('#checkTick .ticket').html(Html)
    //$('#checkSaveTicket .ticket').html(Html)
}

// 封装初始化获取的 选中
var arrTwo = [];

function initFn() {
    var arr = $('#account .custom_lis>li>span>i');

    for (var i = 0; i < arr.length; i++) {
        if ($(arr[i]).hasClass('icon-chenggong1')) {
            var ids = $(arr[i]).attr('id');
            arrTwo.push(ids);
        }
    }
}

initFn()
// 循环点击的对勾
$(document).on('click', '#account .custom_lis>li', function () {
    if ($(this).find('i').hasClass('icon-chenggong1 green')) {
        $(this).find('i').removeClass('icon-chenggong1 green')
        $(this).find('i').attr('status', '0')
    } else {
        $(this).find('i').addClass('icon-chenggong1 green')
        $(this).find('i').attr('status', '1')
    }
})