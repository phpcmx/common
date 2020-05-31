# common
一些快捷常用类库

## 全部功能
**trait**
- 简单单例 SimpleSingleton

**tool**
- 时间打点计时 TickTime

**function**
- 获取客户端时间 ClientUnit::getIp()

## 版本实现

### 版本 1.0.1
- 简单单例 SimpleSingleton

### 版本 1.0.2
- 获取客户端时间 ClientUnit::getIp()

### 版本 1.0.3
- 时间打点计时 TickTime::getInstance()->tick(1);

### 版本 1.0.4
- 优化TickTime的使用方式

### 版本 1.0.5
- 增加类似yaf的简易app，便于开发小型临时项目、自用后台等。无外部依赖，便于迁移
- 可以直接复制appdemo文件夹创建新的小型app