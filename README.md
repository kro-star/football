Не рациональное решение задачи. Сделано с использованием БД, но проще и менее нагруженно можно было сделать просто с функциями и массивом команд.
Для работы проекта нужно развернуть БД, которая находиться в файле: football-matches.sql.

Условия задачи:

Необходимо сгенерировать календарь футбольного чемпионата на один сезон.
В чемпионате принимают участие 20 команд.
Регламент турнира (правила составления календаря):
1. Каждая команда играет с каждой по две игры, одну игру дома и одну на выезде.
2. Исходя из первого пункта, каждая команда проведёт 19 игр дома и 19 в гостях, т.е. всего 38 матчей.
3. Всего будет сыграно 38 туров, в каждом из которых по 10 матчей.
4. Весь календарь делится на два круга, первые 19 туров и вторые 19 туров (Круг - это половина турнира).
5. Каждая команда с каждой играет дважды, один раз встречаясь в первом круге и один раз во втором.
6. Матчи отдельно взятой команды должны чередоваться: игра дома, игра в гостях, игра дома, в гостях и т.д. Допустимы исключения, когда два матча подряд могут идти "дома" или наоборот "в гостях".
7. В рамках одного тура должны учавствовать все 20 команд, без повторений и пропусков.

Задача:

1. Написать скрипт который будет выводить результат на экран. Нажали F5 - получили новый вариант календаря.
Вывести таблицей примерно следующего формата:
1 Тур
Ливерпуль - Челси
Арсенал - Фулхэм
...
2 Тур
Дерби Каунти - Ливерпуль
Норвич Сити - Лестер Сити
...
... и так далее
3. Оформить вывод на страницу с помощью CSS.
4. По клику на одну из команд в таблице подсвечиваются все 38 матчей с её участием, как бы выделяесь на фоне остального расписания.
