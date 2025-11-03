# Tabulator Table Builder

This class provides a fluent interface for building Tabulator table configurations.
It supports adding columns, setting data sources, configuring layout, pagination,
sorting, filtering, responsiveness, and more.


### Basic Example:
```php
use App\Helpers\TabulatorBuilder;

$tableConfig = TabulatorBuilder::make()
->addTextColumn('Name', 'full_name')
->addTextColumn('Role', 'role')
->addTextColumn('Location', 'region')
->data($members)
->paginationLocal(20)
->layoutFitColumns()
->headerFilters()
->movableColumns()
->responsiveLayout()
->toArray();
```

### Advanced Example:
```php
$tableConfig = TabulatorBuilder::make()
->addTextColumn('ID', 'id', false, false) // Not sortable, not filterable
->addTextColumn('Full Name', 'full_name')
->addSelectColumn('Role', 'role', ['Admin', 'User', 'Manager'])
->addNumberColumn('Age', 'age')
->addCheckboxColumn('Active', 'is_active')
->addActionColumn('Actions', function($cell) {
return '<button class="edit-btn">Edit</button>';
})
->data($users)
->paginationLocal(25)
->layoutFitColumns()
->initialSort('full_name', 'asc')
->height('500px')
->selectable(true, 'click')
->tooltips()
->toArray();
```

### Ajax Example:
```php
$tableConfig = TabulatorBuilder::make()
->addTextColumn('Name', 'full_name')
->addTextColumn('Role', 'role')
->ajaxUrl(route('api.members'))
->paginationRemote(route('api.members'), 50)
->layoutFitColumns()
->toArray();
```

### In your controller:
```php
public function index()
{
$members = Member::all();

$tableConfig = TabulatorBuilder::make()
->addTextColumn('Name', 'full_name')
->addTextColumn('Role', 'role')
->addTextColumn('Location', 'region')
->data($members)
->paginationLocal(20)
->layoutFitColumns()
->toArray();

return view('members.index', compact('tableConfig'));
}
```

### In your Blade view:
```html
<div id="member-table" data-tabulator-config='@json($tableConfig)'></div>
@vite(['resources/js/fractional-board/member-table.js'])
```

### And in your JavaScript file:
```javascript
// resources/js/fractional-board/member-table.js
document.addEventListener('DOMContentLoaded', function () {
const tableElement = document.getElementById('member-table');
const config = JSON.parse(tableElement.dataset.tabulatorConfig);

new Tabulator("#member-table", config);
});
```
