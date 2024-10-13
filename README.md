# Commands

Проект с набором команд

## Сбор значений

```shell
php bin/console sum-values:from-files mock
```

Команда по указанному пути, проверяет наличие файла `count`, берет от туда значения и суммирует их.

Наименование файла можно поменять, указав параметр `filename`:

```shell
php bin/console sum-values:from-files mock file.txt
```

Путь откуда начинать поиск можно изменить, указав параметр:

```shell
php bin/console sum-values:from-files pathToDir
```