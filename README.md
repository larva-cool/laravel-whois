# laravel-whois

<p align="center">
    <a href="https://github.com/larvatecn/laravel-whois/actions/workflows/php.yml"><img src="https://github.com/larvatecn/laravel-whois/actions/workflows/php.yml/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/larva/laravel-whois"><img src="https://poser.pugx.org/larva/laravel-whois/v/stable" alt="Stable Version"></a>
    <a href="https://packagist.org/packages/larva/laravel-whois"><img src="https://poser.pugx.org/larva/laravel-whois/downloads" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/larva/laravel-whois"><img src="https://poser.pugx.org/larva/laravel-whois/license" alt="License"></a>
</p>

基于 [io-developer/php-whois](https://github.com/io-developer/php-whois) 的 Laravel WHOIS 查询扩展，支持域名解析、结构化查询和原始响应获取。

## 环境需求

- PHP 8.2 或更高版本
- Laravel 11、12 或 13
- PHP JSON 扩展

## 安装

```bash
composer require larva/laravel-whois
```

扩展支持 Laravel 包自动发现，无需手动注册服务提供者或 Facade。

## 使用

在需要查询的类中引入 Facade：

```php
use Larva\Whois\Whois;
```

### 结构化查询

```php
$info = Whois::lookup('www.example.com');
```

查询成功时返回数组；无法获取结构化信息时返回 `null`。数组包含以下字段：

| 字段 | 说明 |
| --- | --- |
| `parser_type` | 响应解析器类型 |
| `name` | 域名 |
| `registrar` | 注册商 |
| `owner` | 所有者信息 |
| `whois_server` | WHOIS 服务器 |
| `states` | 域名状态数组 |
| `name_servers` | DNS 服务器数组 |
| `dnssec` | DNSSEC 信息 |
| `creation_date` | 注册时间，`Illuminate\Support\Carbon` 实例 |
| `expiration_date` | 过期时间，`Illuminate\Support\Carbon` 实例 |
| `updated_date` | 更新时间，`Illuminate\Support\Carbon` 实例 |
| `response_raw` | 原始 WHOIS 响应 |

### 获取完整查询对象

```php
use Iodev\Whois\Modules\Tld\TldInfo;

/** @var TldInfo|null $info */
$info = Whois::lookupInfo('example.com');
```

该方法返回 `TldInfo` 对象或 `null`，可访问解析库提供的完整字段和原始响应。

### 获取原始响应

```php
$raw = Whois::lookupRaw('example.com');
```

### 解析域名

查询方法支持传入主域名、子域名或包含 URL 的字符串，并会自动提取可注册域名：

```php
$domain = Whois::getDomain('https://www.example.com/path');
// example.com

$domain = Whois::getDomain('www.example.co.uk');
// example.co.uk
```

## 异常处理

- 域名无效时可能抛出 `Larva\Whois\IllegalDomainException` 或 `Pdp\CannotProcessHost`。
- WHOIS 服务器连接、响应或解析失败时，可能抛出 `Iodev\Whois\Exceptions\WhoisException` 及其子类。

查询结果取决于对应 WHOIS 服务器返回的数据，执行在线查询时请确保网络可以访问相关 WHOIS 服务。

## 测试

```bash
composer test
```

部分测试会访问真实 WHOIS 服务器，需要可用的网络连接。
