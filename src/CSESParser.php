<?php

namespace CSES;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use RuntimeException;

class CSESParser {
    private $filePath;
    private $data;
    public $version;
    public $subjects = [];
    public $schedules = [];

    // 构造函数，初始化文件路径并加载和解析数据
    public function __construct($filePath) {
        $this->filePath = $filePath;
        $this->_loadFile();
        $this->_parseData();
    }

    // 加载文件内容并解析为YAML格式
    private function _loadFile() {
        try {
            if (!file_exists($this->filePath)) {
                throw new RuntimeException("文件 {$this->filePath} 未找到");
            }
            
            $fileContents = file_get_contents($this->filePath);
            $this->data = Yaml::parse($fileContents);
        } catch (ParseException $e) {
            throw new RuntimeException("YAML 错误: " . $e->getMessage());
        }
    }

    // 解析数据并填充类属性
    private function _parseData() {
        if (empty($this->data)) return;

        $this->version = $this->data['version'] ?? 1;

        // 解析科目
        foreach ($this->data['subjects'] ?? [] as $subject) {
            $this->subjects[] = [
                'name' => $subject['name'],
                'simplified_name' => $subject['simplified_name'] ?? null,
                'teacher' => $subject['teacher'] ?? null,
                'room' => $subject['room'] ?? null
            ];
        }

        // 解析课程表
        foreach ($this->data['schedules'] ?? [] as $schedule) {
            $classes = [];
            foreach ($schedule['classes'] ?? [] as $cls) {
                $classes[] = [
                    'subject' => $cls['subject'],
                    'start_time' => $cls['start_time'],
                    'end_time' => $cls['end_time']
                ];
            }

            $this->schedules[] = [
                'name' => $schedule['name'],
                'enable_day' => $schedule['enable_day'],
                'weeks' => $schedule['weeks'],
                'classes' => $classes
            ];
        }
    }

    // 获取所有科目
    public function getSubjects() {
        return $this->subjects;
    }

    // 获取所有课程表
    public function getSchedules() {
        return $this->schedules;
    }

    // 根据天数获取课程表
    public function getScheduleByDay($day) {
        foreach ($this->schedules as $schedule) {
            if ($schedule['enable_day'] === $day) {
                return $schedule['classes'];
            }
        }
        return [];
    }

    // 静态方法，判断文件是否为CSES文件
    public static function isCsesFile($filePath) {
        try {
            if (!file_exists($filePath)) return false;
            
            $fileContents = file_get_contents($filePath);
            $data = Yaml::parse($fileContents);
            
            return isset($data['version']) && 
                   isset($data['subjects']) && 
                   isset($data['schedules']);
        } catch (Exception $e) {
            return false;
        }
    }
}