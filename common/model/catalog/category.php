<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM oc_category c LEFT JOIN oc_category_description cd ON (c.category_id = cd.category_id) LEFT JOIN oc_category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = :category_id AND cd.language_id = :language_id AND c2s.store_id = :store_id AND c.status = :status",
            [
                ':category_id' => $category_id,
                ':language_id' => $this->config->get('config_language_id'),
                ':store_id' => $this->config->get('config_store_id'),
                ':status' => 1
            ]);

		return $query->row;
	}

	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM oc_category c LEFT JOIN oc_category_description cd ON (c.category_id = cd.category_id) LEFT JOIN oc_category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = :parent_id AND cd.language_id = :language_id AND c2s.store_id = :store_id AND c.status = :status ORDER BY c.sort_order, LCASE(cd.name)",
            [
                ':parent_id' => $parent_id,
                ':language_id' => $this->config->get('config_language_id'),
                ':store_id' => $this->config->get('config_store_id'),
                ':status' => 1

            ]);

		return $query->rows;
	}

	public function getCategoryFilters($category_id) {
		$implode = [];

		$query = $this->db->query("SELECT filter_id FROM oc_category_filter WHERE category_id = :category_id",
            [
                ':category_id' => $category_id
            ]);

		foreach ($query->rows as $result) {
			$implode[] = (int)$result['filter_id'];
		}

		$filter_group_data = [];

		if ($implode) {
			$filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM oc_filter f LEFT JOIN oc_filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN oc_filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND fgd.language_id = :language_id GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)",
                [
                    ':language_id' => $this->config->get('config_language_id'),
                ]);

			foreach ($filter_group_query->rows as $filter_group) {
				$filter_data = [];

				$filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM oc_filter f LEFT JOIN oc_filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND f.filter_group_id = :filter_group_id AND fd.language_id = :language_id ORDER BY f.sort_order, LCASE(fd.name)",
                    [
                        ':language_id' => $this->config->get('config_language_id'),
                        ':filter_group_id' => $filter_group['filter_group_id']
                    ]);

				foreach ($filter_query->rows as $filter) {
					$filter_data[] = [
						'filter_id' => $filter['filter_id'],
						'name'      => $filter['name']
                    ];
				}

				if ($filter_data) {
					$filter_group_data[] = [
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
					];
				}
			}
		}

		return $filter_group_data;
	}

	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM oc_category_to_layout WHERE category_id = :category_id AND store_id = :store_id",
            [
                ':category_id' => $category_id,
                ':store_id' => $this->config->get('config_store_id')
            ]);

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM oc_category c LEFT JOIN oc_category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = :parent_id AND c2s.store_id = :store_id AND c.status = :status",
            [
                ':parent_id' => $parent_id,
                ':store_id' => $this->config->get('config_store_id'),
                ':status' => 1
            ]);

		return $query->row['total'];
	}
}