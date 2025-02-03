# CSES PHP Library

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
[![Packagist Version]([https://img.shields.io/packagist/v/yourname/cses-php](https://packagist.org/packages/pylxu/cses-php))]([https://packagist.org/packages/yourname/cses-php](https://packagist.org/packages/pylxu/cses-php))

用于解析和生成 CSES（Curriculum Schedule Exchange Standard）课程表格式的PHP库

**本仓库部分代码以及文件说明由DeepSeek提供支持*

## 📦 安装

通过 Composer 安装：

```bash
composer require yourname/cses-php
```

## 🚀 快速开始

### 解析CSES文件
```php
use CSES\CSESParser;

try {
    $parser = new CSESParser('schedule.cses.yml');
    
    // 获取所有科目
    $subjects = $parser->getSubjects();
    
    // 获取星期一的课程
    $mondayClasses = $parser->getScheduleByDay('mon');
    
} catch (RuntimeException $e) {
    echo "错误: " . $e->getMessage();
}
```

### 生成CSES文件
```php
use CSES\CSESGenerator;

$generator = new CSESGenerator();

// 添加科目
$generator->addSubject(
    '高等数学',
    '高数',
    '王教授',
    '逸夫楼203'
);

// 添加课程安排
$generator->addSchedule('周一', 'mon', 'all', [
    [
        'subject' => '高等数学',
        'start_time' => '08:00',
        'end_time' => '09:35'
    ]
]);

// 保存文件
$generator->saveToFile('new_schedule.cses.yml');
```

## 📚 详细文档

### CSESParser 类
| 方法 | 说明 |
|------|------|
| `__construct(string $filePath)` | 初始化解析器并加载文件 |
| `getSubjects(): array` | 返回所有科目信息 |
| `getSchedules(): array` | 返回完整课程表结构 |
| `getScheduleByDay(string $day): array` | 按星期几获取课程（支持：mon/tue/wed/thu/fri/sat/sun） |
| `isCsesFile(string $filePath): bool` | 静态方法验证文件格式 |

### CSESGenerator 类
| 方法 | 说明 |
|------|------|
| `addSubject(string $name, ?string $simplifiedName, ?string $teacher, ?string $room)` | 添加科目信息 |
| `addSchedule(string $name, string $enableDay, string $weeks, array $classes)` | 添加课程安排 |
| `generateCsesData(): array` | 生成原始数组数据 |
| `saveToFile(string $filePath)` | 保存为YAML文件 |

## 📝 数据格式说明

### 科目结构
```yaml
subjects:
  - name: "科目全称"
    simplified_name: "简称"    # 可选
    teacher: "教师姓名"        # 可选
    room: "教室编号"          # 可选
```

### 课程表结构
```yaml
schedules:
  - name: "周一"
    enable_day: "mon"        # 启用星期（小写缩写）
    weeks: "all"             # 周次类型：all/odd/even
    classes:
      - subject: "科目名称"
        start_time: "08:00"
        end_time: "09:35"
```

## ⚠️ 注意事项
1. 时间格式必须使用 `HH:MM` 24小时制
2. `enable_day` 只接受标准英文缩写：mon/tue/wed/thu/fri/sat/sun
3. 周次类型 `weeks` 可选值：
   - `all`: 每周
   - `odd`: 单周
   - `even`: 双周

## 🤝 参与贡献
欢迎通过 Issue 报告问题或提交 Pull Request：
1. Fork 项目仓库
2. 创建特性分支 (`git checkout -b feature/your-feature`)
3. 提交更改 (`git commit -am 'Add some feature'`)
4. 推送分支 (`git push origin feature/your-feature`)
5. 创建 Pull Request

## 📄 许可证
本项目基于 [MIT License](LICENSE) 发布
