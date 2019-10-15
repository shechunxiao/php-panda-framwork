<?php

/**
 * 利用cli直接执行PHP文件，从而实现了创建文件了
 */
if (!file_exists('./clitest/b.txt')){
    file_put_contents('./clitest/b.txt',' ');
}