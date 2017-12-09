---
title: cmake梳理
date: 2017-11-12 16:12:58
tags:
---


cmake中没有大小写区分。先看一段完整的CmakeLists.txt文件

```
cmake_minimum_required(VERSION 3.0)

# change add_library default actions. change default libiary type to so
set(BUILD_SHARED_LIBS on)
# show debug verbos message
set(CMAKE_VERBOSE_MAKEFILE on)

project(cmake_test)

include_directories(include)
add_library(utils src/utils.cpp)

add_executable(cmake_test src/main.cpp)
target_link_libraries(cmake_test utils)
```



#### add_library

这个命令的作用是把源文件打包成库。默认情况下，打包成静态库。也可以指定参数。参考下述例子

```
add_library(archive archive.cpp zip.cpp lzma.cpp)        #  ==> libarchive.a
add_library(archive STATIC archive.cpp zip.cpp lzma.cpp) #  ==> libarchive.a
add_library(archive SHARED archive.cpp zip.cpp lzma.cpp) #  ==> libarchive.so
```

默认情况下的动作也是可以更改的。开启BUILD_SHARED_LIBS变量可以使得默认生成动态库

```
SET(BUILD_SHARED_LIBS on)                               # 开启BUILD_SHARED_LIBS变量，改变默认动作
add_library(archive archive.cpp zip.cpp lzma.cpp)       #  ==> libarchive.so
```

另外，这个命令还有一些其他参数，标识生成的库类型以及作用。

##### MODULE 

该参数告诉cmake，add_library编译出来的模块作为插件给其他地方加载。而不是用于链接

##### OBJECT 

使用该参数之后并不会产出任何实际的文件，仅仅是把文件集合起来，作为源文件集给后续流程使用。不能被target_link_libraries()和add_custom_command(TARGET)使用

##### 示例

```
add_library(archive MODULE 7z.cpp)
```

```
add_library(archive OBJECT archive.cpp zip.cpp lzma.cpp)
add_library(archiveExtras STATIC $<TARGET_OBJECTS:archive> extras.cpp)
add_executable(test_exe $<TARGET_OBJECTS:archive> test.cpp)
```

加了MODULE和不加差别如下编译输出所示

![img](./so类型.png)



#### include_directories

被这个命令添加的属性，会以 `-I 或者 -isystem` 为前缀添加到编译条件中。例如

```
cmake_minimum_required(VERSION 2.8)

aux_source_directory(src sources2)
include_directories(include)

add_library(utils ${sources2})
```

执行编译输出如下

![img](./include_directories.png)



#### add_executable

生成可执行文件



#### target_link_libraries

向目标中添加库文件



#### aux_source_directory

收集源文件，文件列表不包含头文件



#### project

设置工程名称



#### set

设置变量


