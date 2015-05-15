@extends('framework')

@section('content')
<div class="container">
                <form class="from-horizontal book-form"   method="POST">
                    <h3 class="">欢迎使用同行网预约系统</h3>
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label">景点名称</label>
                            <div class="controls">
                                <select class="input-block-level" name="jingdian" id="jingdian">
                                @foreach ($jingdianConf as $k => $v)
                                <option value="{{$k}}" @if($k == $jingdian)selected @endif>{{$v}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">商家名称</label>
                            <div class="controls">
                                <select class="input-block-level" name="shangjia" id="shangjia">
                                @foreach ($shangjiaConf as $k => $v)
                                <option value="{{$k}}" @if($k == $shangjia)selected @endif>{{$v}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">出行日期</label>
                            <div class="controls">
                                <input class="input-block-level" placeholder="点击选择出行日期" style="background-color:#FFF" name="travel_at" id="travel_at" type="text" readonly="readonly" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">主要联系人</label>
                            <div class="controls">
                            <input class="input-block-level" placeholder="请输入主要联系人姓名" name="name" id="name" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">手机</label>
                            <div class="controls">
                                <input class="input-block-level" placeholder="请输入主要联系人手机" name="telephone" id="telephone" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">团购券号(多个券号以逗号，分隔)</label>
                            <div class="controls">
                                <textarea class="input-block-level" rows="3" name="ticket" id="ticket"  placeholder="多个券号以逗号分隔"></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">备注(可填写人数信息)</label>
                            <div class="controls">
                                <textarea class="input-block-level" rows="2" name="info" id="info" placeholder="备注信息"></textarea>
                            </div>
                        </div>
                        <button type="button" id="book"  class="btn btn-large btn-warning btn-block">立即预约</button>
                    </fieldset>
                </form>
</div>
@endsection
@section('js')
<script type="text/javascript" src="/lib/laydate/laydate.js"></script>
<script type="text/javascript">
!function(){
    laydate.skin('molv');//切换皮肤，请查看skins下面皮肤库
    laydate({
        elem: '#travel_at',
            festival: true, //显示节日
            min: laydate.now(+1), 
    });
}();

</script>
<script>
$(document).ready(function(){
    $('#book').click(function(){
        var data = {};
        data['jingdian'] = $("#jingdian").val();
        data['shangjia'] = $("#shangjia").val();
        data['travel_at'] = $("#travel_at").val();
        if(data['travel_at'] == ''){
            alert('出发日期必填');
            return false;
        }
        data['name'] = $("#name").val();
        if(data['name'] == ''){
            alert("主要联系人必填");
            return false;
        }
        data['telephone'] = $("#telephone").val();
        if(data['telephone'] == ''){
            alert("手机必填");
            return false;
        }
        var reg = new RegExp("^[0-9]{11}$");
        if( ! reg.test(data['telephone'])){
            alert("请输入正确的电话号码");
            return false;
        }
        data['ticket'] = $("#ticket").val();
        if(data['ticket'] == ''){
            alert("团购券号必填");
            return false;
        }
        data['info'] = $("#info").val();
        $.ajax({
            url: '/book',
                data: data,
                dataType:'json',
                type:'POST',
                success: function(res) {           
                    if(res['status'] == 0 ){
                        alert('恭喜，预定成功，请留意我们的短信！');
                        window.location.reload(true);
                    }else{
                        alert(res['info']);
                    }
                    return false;
                },
        });
        return false;
    });
});
</script>
@endsection
