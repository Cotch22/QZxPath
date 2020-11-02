# QZxPath
强智教务系统基于XPath的API

```
/src/ImgIdenfy.php 验证码识别
/src/QZxPath.php 强智xPath接口
/src/Request.php Curl封装
example.php 提供给移动端APP的接口示例
```

适用于不支持/app.do接口的教务系统或者想实现更多功能的你

# 用法

**实例化**

`baseUrl` 教务系统URL(`http://jwxt.xxx.edu.cn/jsxsd/`)  
`isVerifyCode` 是否需要验证码(`true`/`fase`)

```
QZxPath($baseUrl, $isVerifyCode)
```

| API | 功能 |
| --- | --- |
| login | [登录教务系统](Doc.md#登录教务系统) |
| auth | [登录教务系统并返回用户信息](Doc.md#登录教务系统并返回用户信息) |
| logout | [退出教务系统](Doc.md#退出教务系统) |
| getSkd | [获取课表](Doc.md#获取课表) |
| getMark | [获取成绩](Doc.md#获取成绩) |
| getScore | [获取学分](Doc.md#获取学分) |
| getPJList | [获取评教列表](Doc.md#获取评教列表) |
| doAutoPJ | [自动评教](Doc.md#自动评教) |

关于API返回格式可前往[Doc](Doc.md)查看

# 效率
类爬虫型API最大的问题就在于效率，这套API经过我两年的使用修改，已经趋于稳定，效率也不错。  
尽量减少request教务系统的次数，优化XPath逻辑代码。  
例如登录、获取课表、获取成绩这类只需要一次request教务系统的接口，只会在request教务系统的耗时上增加10ms~40ms的耗时。
而必要繁琐的请求，例如自动评教，需要多次request教务系统，可能会消耗数秒。

# 感谢
- [WindrunnerMax/SWVerifyCode](https://github.com/WindrunnerMax/SWVerifyCode)

# 声明
这套API已经被部署在了几个小程序上，你大可放心使用，你可以为你的同学提供更便利的教务系统体验。  
你可以随意使用、修改这套API，我不保留这套API的版权，我认为我只是在利用强智教务系统。  
不要尝试入侵教务系统，我们只利用它公开提供给我们的能力。

如果你是强智教务系统的官方人员，认为这套API对你们造成影响或存在侵权，欢迎联系我。