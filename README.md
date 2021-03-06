## CatchAdmin
- 该项目采用前后端分离，所以 PHP 作为 API 开发，vue 作为后台开发，两个项目是分开的，单独部署。
## 这是 vue 分支 开发中
- [vue 项目地址](https://gitee.com/catchamin/catch-admin-vue)

### 环境要求
- php7.1+ (需以下扩展)
    - mbstring
    - json
    - openssl
    - xml
    - pdo
- nginx
- mysql

### install
- curl -sS http://install.phpcomposer.com/installer | php
- composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
- composer update
- php think catch:install 

### Use
- 配置虚拟域名 OR 在根目录下执行 php think run
- yourUrl/login
- 默认用户名 admin@gmail.com 密码 admin

### Problem
> SQLSTATE[42000]: Syntax error or access violation: 1067 Invalid default value for 'updated_at'

> 设置 sql_mode;
```
show variables like 'sql_mode' ; 
remove 'NO_ZERO_IN_DATE,NO_ZERO_DATE'
```
> SET GLOBAL sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'

### Talking
- 可以提 ISSUE，请按照 issue 模板提问
- 欢迎进入 Q 群，可以及时反馈一些问题。
- ![输入图片说明](https://images.gitee.com/uploads/images/2018/1219/110300_0257b6c0_810218.jpeg "微信图片_20181219105915.jpg")

仅供学习

## 体验地址

[体验地址](http://catch.njphper.com/login)
- 账号: test@catch.com 
- 密码: 123456
