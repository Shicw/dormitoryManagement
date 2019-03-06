学校宿舍管理系统
===============
 + 基于ThinkPHP5和LayUI框架开发

> ThinkPHP5的运行环境要求PHP5.4以上。

## 目录结构

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录（可以更改）
│  ├─admin              管理员模块目录
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  └─view            视图目录
│  ├─user               普通用户模块目录
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  └─view            视图目录
│  │
|  ├─AdminBaseController.php  admin模块的父类控制器
│  ├─command.php              命令行工具配置文件
│  ├─common.php               公共函数文件
│  ├─config.php               公共配置文件
│  ├─route.php                路由配置文件
│  ├─tags.php                 应用行为扩展定义文件
│  └─database.php             数据库配置文件
│
├─public                WEB目录（对外访问目录）
│
├─thinkphp              框架系统目录
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~