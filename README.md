## Установка

```
composer require beta/collection.base
```

**Пример работы с коллекцией**

```php
use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Collection;

$items = [
    new ArrayDataCollectionItem(['name' => 'John', 'age' => 30]),
    new ArrayDataCollectionItem(['name' => 'Jane', 'age' => 25]),
    new ArrayDataCollectionItem(['name' => 'Mike', 'age' => 35]),
    new ArrayDataCollectionItem(['name' => 'Kile', 'age' => 25]),
];

$collection = new Collection($items);

$filteredCollection = $collection->filterByKey('age', 30, 35); // новая коллекция с 1 и 3 элементом исходной
$groupedCollection = $collection->groupByKey('age'); // коллекция из сгруппированных коллекций (GroupCollection) по значению age 

$names = $collection->column('name'); // Все значения name
$uniqueAges = $collection->unique('age'); // все не повторяющиеся значения age

$firstItem = $collection->first(); // первый элемент коллекции
```
