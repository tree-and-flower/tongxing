<?php 
namespace App\Http\Controllers;
use Overtrue\Wechat\Server;
use Overtrue\Wechat\Message;
use Overtrue\Wechat\Menu;
use Overtrue\Wechat\MenuItem;
use Config;
use Log;
class WechatController extends Controller {
    const TEXT_SUBSCRIBE = <<<END
欢迎关注深圳[同行旅游]景点门票O2O服务号。输入景点名，即可查询该景点门票价格、开放时间等详细信息。如海洋世界、世界之窗、欢乐谷、锦绣中华民俗村等。
END;
    const TEXT_DEFAULT = <<<END
请输入景点名，即可查询该景点门票价格、开放时间等详细信息。如海洋世界、世界之窗、欢乐谷、锦绣中华民俗村等。
END;
    const TEXT_TICKET_BOOK = <<<END
输入景点名（如世界之窗）查询门票价格、开放时间等详细信息。门票预订接受两种预订方式，预订时间截止到出行时间的前天晚上12点。
预订方式1：直接回复信息，并且以“预订”或者“预定”开始
格式：[预订：][姓名][联系方式][景点名称][预订人数]
例：预订：张三，18828282828，世界之窗，3人
预订方式2：
通过以下联系进行预订：
QQ:306824269
TEL:13422872077
END;
    const TEXT_TICKET_BOOK_SUCCESS = <<<END
感谢您的预订，我们已经成功收到您的预订信息，我们的工作人员会统一确认和处理您的预订信息，无效信息会直接过滤掉，请留意我们的确认信息，如需帮助请直接联系：
QQ:306824269
TEL:13422872077
END;
    const TEXT_USE_INFO = <<<END
请输入景点名，即可查询该景点门票价格、开放时间等详细信息。
如海洋世界、世界之窗、欢乐谷、锦绣中华民俗村等，预订、退订请仔细阅读说明。
END;
    const TEXT_TICKET_UNBOOK = <<<END
已经预订的用户直接通过以下联系方式取消预订，截止时间为出行时间的前天晚上12点。
QQ:306824269
TEL:13422872077
END;
    const TEXT_O2O = <<<END
线上到线下模式的一种实际场景，专注于提供目的地景点门票预订的极致用户体验。
END;

    /*
     * 处理微信的请求信息
     *
     * @return string
     */
    public function serve(Server $server){
        $keyword = Config::get('keyword');
        //订阅事件消息回复
        $server->event('subscribe', function ($event){
            return Message::make('text')->content(self::TEXT_SUBSCRIBE);
        });
        //普通消息只有文本类型时进行模糊匹配
        $server->message(function($msg) use ($keyword) {
            $msgContent = trim($msg->Content);
            if ('text' == $msg->MsgType) {
                if (0 === strpos($msgContent, '预订')) {
                    return Message::make('text')->content(self::TEXT_TICKET_BOOK_SUCCESS);
                    return 'aa';
                }
                foreach ($keyword as $word => $content) {
                    if (false !== strpos($msgContent, $word)) {
                        return Message::make('text')->content($content);
                    }
                }
            }
            return Message::make('text')->content(self::TEXT_DEFAULT);
            //Log::info("info",[info => 'info']);
        });
        $server->event(function ($event){
                if($event->EventKey == 'CLICK_TICKET_BOOK'){
                    return Message::make('text')->content(self::TEXT_TICKET_BOOK);
                }
                if($event->EventKey == 'CLICK_USE_INFO'){
                    return Message::make('text')->content(self::TEXT_USE_INFO);
                }
                if($event->EventKey == 'CLICK_TICKET_UNBOOK'){
                    return Message::make('text')->content(self::TEXT_TICKET_UNBOOK);
                }
                if($event->EventKey == 'CLICK_O2O'){
                    return Message::make('text')->content(self::TEXT_O2O);
                }
        });
        return $server->serve();
    }

    /**
     * 设置菜单
     */
    public function setMenu(Menu $menu){
        $button1 = new MenuItem('景点门票');
        $button2 = new MenuItem('自助服务');
        $menus = array(
            $button1->buttons(array(
                new MenuItem('海洋世界', 'view', 'http://i.meituan.com/deal/29307867.html?stid=360411583830086912_b2_c0_e10070784521875509948_d10360406958760447488_a%E6%B5%B7%E6%B4%8B%E4%B8%96%E7%95%8C'),
            )),
            new MenuItem('预约', 'view', 'http://115.29.51.171/book'),
            $button2->buttons(array(
                new MenuItem('使用说明', 'click', 'CLICK_USE_INFO'),
                new MenuItem('退订说明', 'click', 'CLICK_TICKET_UNBOOK'),
                new MenuItem('门票O2O', 'click', 'CLICK_O2O'),
                new MenuItem('门票预订', 'click', 'CLICK_TICKET_BOOK'),
            )),
        );

        try {
            $menu->set($menus);
            echo '设置成功！';
        } catch (\Exception $e) {
            echo '设置失败：' . $e->getMessage();
        }
    }
}
