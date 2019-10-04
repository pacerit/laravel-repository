<?php

namespace PacerIT\LaravelRepository\Repositories\Traits;

use Yajra\DataTables\EloquentDataTable;

/**
 * Trait WithDatatable.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-08-07
 */
trait WithDatatable
{
    /**
     * Make datatable response.
     *
     * @param array $columns
     *
     * @return EloquentDataTable
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-31
     */
    public function datatable(array $columns): EloquentDataTable
    {
        $this->applyCriteria();

        return datatables()->eloquent($this->getEntity()->select($columns));
    }
}
