<?php

namespace App\Helpers\Tabulator;

class Tabulator
{
    protected array $columns = [];
    protected array $options = [];
    protected ?array $data = null;
    protected string $ajaxUrl = '';

    public static function make(): self
    {
        return new self();
    }

    // COLUMN METHODS
    public function addColumn(string $title, string $field, array $options = []): self
    {
        $this->columns[] = array_merge([
            'title' => $title,
            'field' => $field,
        ], $options);

        return $this;
    }

    public function addColumns(array $columns): self
    {
        foreach ($columns as $column) {
            $this->addColumn($column['title'], $column['field'], $column['options'] ?? []);
        }
        return $this;
    }

    // DATA METHODS
    public function data($data): self
    {
        $this->data = is_array($data) ? $data : $data->toArray();
        return $this;
    }

    public function ajaxUrl(string $url): self
    {
        $this->ajaxUrl = $url;
        $this->options['ajaxURL'] = $url;
        return $this;
    }

    // LAYOUT METHODS
    public function layoutFitColumns(): self
    {
        $this->options['layout'] = 'fitColumns';
        return $this;
    }

    public function layoutFitData(): self
    {
        $this->options['layout'] = 'fitData';
        return $this;
    }

    public function layoutFitDataFill(): self
    {
        $this->options['layout'] = 'fitDataFill';
        return $this;
    }

    // PAGINATION METHODS
    public function paginationLocal(int $size = 20, int $page = 1): self
    {
        $this->options['pagination'] = 'local';
        $this->options['paginationSize'] = $size;
        $this->options['paginationInitialPage'] = $page;
        return $this;
    }

    public function paginationRemote(string $url, int $size = 20): self
    {
        $this->options['pagination'] = 'remote';
        $this->options['paginationSize'] = $size;
        $this->options['ajaxURL'] = $url;
        return $this;
    }

    // SORTING METHODS
    public function sortable(bool $enabled = true): self
    {
        $this->options['sortable'] = $enabled;
        return $this;
    }

    public function initialSort(string $field, string $direction = 'asc'): self
    {
        $this->options['initialSort'] = [
            ['column' => $field, 'dir' => $direction]
        ];
        return $this;
    }

    // FILTERING METHODS
    public function headerFilters(bool $enabled = true): self
    {
        $this->options['headerFilter'] = $enabled;
        return $this;
    }

    // RESPONSIVE METHODS
    public function responsiveLayout(bool $enabled = true): self
    {
        $this->options['responsiveLayout'] = $enabled;
        return $this;
    }

    public function responsiveLayoutCollapseStartOpen(bool $enabled = true): self
    {
        $this->options['responsiveLayoutCollapseStartOpen'] = $enabled;
        return $this;
    }

    // HEIGHT METHODS
    public function height(string $height): self
    {
        $this->options['height'] = $height;
        return $this;
    }

    public function maxHeight(string $maxHeight): self
    {
        $this->options['maxHeight'] = $maxHeight;
        return $this;
    }

    // SELECTION METHODS
    public function selectable(bool $enabled = true, string $mode = 'highlight'): self
    {
        $this->options['selectable'] = $enabled;
        $this->options['selectableRangeMode'] = $mode;
        return $this;
    }

    // TOOLTIPS
    public function tooltips(bool $enabled = true): self
    {
        $this->options['tooltips'] = $enabled;
        return $this;
    }

    // ADDITIONAL OPTIONS
    public function movableColumns(bool $enabled = true): self
    {
        $this->options['movableColumns'] = $enabled;
        return $this;
    }

    public function resizableColumns(bool $enabled = true): self
    {
        $this->options['resizableColumns'] = $enabled;
        return $this;
    }

    public function columnHeaderVertAlign(string $align = 'top'): self
    {
        $this->options['columnHeaderVertAlign'] = $align;
        return $this;
    }

    // CUSTOM OPTIONS (for anything not covered above)
    public function option(string $key, $value): self
    {
        $this->options[$key] = $value;
        return $this;
    }

    // COLUMN OPTION HELPERS (chainable column configuration)
    public function withColumnOptions(array $columnOptions): self
    {
        if (!empty($this->columns)) {
            $lastIndex = count($this->columns) - 1;
            $this->columns[$lastIndex] = array_merge($this->columns[$lastIndex], $columnOptions);
        }
        return $this;
    }

    // BUILD METHODS
    public function toArray(): array
    {
        $config = [
            'columns' => $this->columns,
        ];

        if ($this->data) {
            $config['data'] = $this->data;
        }

        return array_merge($config, $this->options);
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    // QUICK COLUMN TYPES (for common column patterns)
    public function addTextColumn(string $title, string $field, bool $sortable = true, bool $filterable = true): self
    {
        return $this->addColumn($title, $field, [
            'sortable' => $sortable,
            'headerFilter' => $filterable ? 'input' : false,
        ]);
    }

    public function addNumberColumn(string $title, string $field, bool $sortable = true, bool $filterable = true): self
    {
        return $this->addColumn($title, $field, [
            'sortable' => $sortable,
            'headerFilter' => $filterable ? 'number' : false,
            'sorter' => 'number',
        ]);
    }

    public function addSelectColumn(string $title, string $field, array $options, bool $sortable = true): self
    {
        return $this->addColumn($title, $field, [
            'sortable' => $sortable,
            'headerFilter' => true,
            'headerFilterParams' => [
                'values' => array_combine($options, $options),
            ],
        ]);
    }

    public function addCheckboxColumn(string $title, string $field, bool $sortable = true): self
    {
        return $this->addColumn($title, $field, [
            'formatter' => 'tickCross',
            'sortable' => $sortable,
            'headerFilter' => 'tickCross',
            'headerFilterParams' => ['tristate' => true],
        ]);
    }

    public function addActionColumn(string $title = 'Actions', callable $formatter = null): self
    {
        $options = [
            'title' => $title,
            'field' => 'actions',
            'formatter' => 'html',
            'sortable' => false,
            'headerFilter' => false,
            'resizable' => false,
            'width' => 100,
        ];

        if ($formatter) {
            $options['formatter'] = $formatter;
        }

        $this->columns[] = $options;
        return $this;
    }
}
