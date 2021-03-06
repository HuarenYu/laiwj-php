@extends('layouts.app')

@section('title', '预订')

@section('stylesheet')
    <link rel="stylesheet" href="/jquery-ui/jquery-ui.min.css">
@endsection

@section('content')
<div class="order-form">
    <div class="price">
        <div><sup>￥</sup>{{ $inn->price}}</div>
        <div>每人每天</div>
    </div>
    <form id="orderForm">
        {{ csrf_field() }}
        <input name="inn_id" type="hidden" value="{{ $inn->id }}">
        <div class="form">
            <div class="form-group-50">
                <label for="">入住</label>
                <input name="start_date" type="text" required>
            </div>
            <div class="form-group-50">
                <label for="">离开</label>
                <input name="end_date" type="text" required>
            </div>
            <div class="form-group-100">
                <label for="">入住人数</label>
                <input name="customer_count" type="text" required>
            </div>
            <div class="form-group-100">
                <label for="">联系人</label>
                <input name="customer_name" type="text" required>
            </div>
            <div class="form-group-100">
                <label for="">联系电话</label>
                <input name="customer_phone" type="text" required>
            </div>
            <div class="form-group-100 submit">
                <button type="submit" class="btn btn-primary btn-block">提交订单</button>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')
<script src="/jquery-ui/jquery-ui.min.js"></script>
<script>
    $.datepicker.regional['zh-CN'] = {
        clearText: '清除',
        clearStatus: '清除已选日期',
        closeText: '关闭',
        closeStatus: '不改变当前选择',
        prevText: '<上月',
        prevStatus: '显示上月',
        prevBigText: '<<',
        prevBigStatus: '显示上一年',
        nextText: '下月>',
        nextStatus: '显示下月',
        nextBigText: '>>',
        nextBigStatus: '显示下一年',
        currentText: '今天',
        currentStatus: '显示本月',
        monthNames: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
        monthNamesShort: ['一','二','三','四','五','六', '七','八','九','十','十一','十二'],
        monthStatus: '选择月份',
        yearStatus: '选择年份',
        weekHeader: '周',
        weekStatus: '年内周次',
        dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
        dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
        dayNamesMin: ['日','一','二','三','四','五','六'],
        dayStatus: '设置 DD 为一周起始',
        dateStatus: '选择 m月 d日, DD',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        initStatus: '请选择日期',
        isRTL: false
    };
    $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
</script>
<script>
    var innSchedule = {!! $inn->schedule !!};
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd',
        beforeShowDay: function(date){
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            return [ innSchedule.indexOf(string) == -1 ]
        }
    });
    $('input[name=start_date]').datepicker();
    $('input[name=end_date]').datepicker();
    $('#orderForm').on('submit', function (e) {
        e.preventDefault();
        API.
        orders.
        add($(this).serialize()).
        then(function (resp) {
            if (resp.statusCode && resp.statusCode === 40001) {
                alert(resp.msg);
                innSchedule = JSON.parse(resp.inn.schedule);
                return;
            }
            window.location = '/user/trip/' + resp.id;
        }).
        fail(function (err) {
            alert(err.responseText);
        });
    });
</script>
@endsection