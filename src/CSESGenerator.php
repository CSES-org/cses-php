<?php

namespace CSES;

use Symfony\Component\Yaml\Yaml;
use RuntimeException;

class CSESGenerator {
    private $version;
    private $subjects = [];
    private $schedules = [];

    // 构造函数，初始化版本号
    public function __construct($version = 1) {
        $this->version = $version;
    }

    // 添加科目
    public function addSubject($name, $simplifiedName = null, $teacher = null, $room = null) {
        $this->subjects[] = [
            'name' => $name,
            'simplified_name' => $simplifiedName,
            'teacher' => $teacher,
            'room' => $room
        ];
    }

    // 添加课程表
    public function addSchedule($name, $enableDay, $weeks, $classes) {
        $processedClasses = [];
        foreach ($classes as $cls) {
            $processedClasses[] = [
                'subject' => $cls['subject'],
                'start_time' => $cls['start_time'],
                'end_time' => $cls['end_time']
            ];
        }

        $this->schedules[] = [
            'name' => $name,
            'enable_day' => $enableDay,
            'weeks' => $weeks,
            'classes' => $processedClasses
        ];
    }

    // 生成CSES数据
    public function generateCsesData() {
        return [
            'version' => $this->version,
            'subjects' => $this->subjects,
            'schedules' => $this->schedules
        ];
    }

    // 将数据保存到文件
    public function saveToFile($filePath) {
        $csesData = $this->generateCsesData();
        try {
            $yamlStr = Yaml::dump($csesData, 4, 4, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
            file_put_contents($filePath, $yamlStr);
        } catch (Exception $e) {
            throw new RuntimeException("写入 {$filePath} 失败: " . $e->getMessage());
        }
    }
}