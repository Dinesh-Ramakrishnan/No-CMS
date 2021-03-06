<?php
class Ajax extends CMS_Controller{
	
	// get restricted project options
	public function get_restricted_project_option($template_id=0){
		$query = $this->db->select('option_id')
			->from('nds_template_option')
			->where('option_type !=','project')
			->or_where('template_id !=',$template_id)
			->get();
		$option_id = array();
		foreach($query->result() as $row){
			$option_id[] = $row->option_id;
		}
		$this->cms_show_json($option_id);
	}
	
	// get restricted table options
	public function get_restricted_table_option($project_id=0){
		$SQL = "SELECT option_id
			FROM nds_template_option
			WHERE option_type <>'table' OR
			template_id NOT IN (SELECT template_id FROM nds_project WHERE project_id=$project_id)";
		$query = $this->db->query($SQL);
		$option_id = array();
		foreach($query->result() as $row){
			$option_id[] = $row->option_id;
		}
		$this->cms_show_json($option_id);
	}
	
	// get restricted column options
	public function get_restricted_column_option($table_id=0){
		$SQL = "SELECT option_id
			FROM nds_template_option
			WHERE option_type <>'column' OR
			template_id NOT IN (
				SELECT template_id 
				FROM nds_project, nds_table 
				WHERE nds_project.project_id=nds_table.project_id AND nds_table.table_id=$table_id)";
		$query = $this->db->query($SQL);
		$option_id = array();
		foreach($query->result() as $row){
			$option_id[] = $row->option_id;
		}
		$this->cms_show_json($option_id);
	}
	
	// get restricted table sibling
	public function get_restricted_table_sibling($table_id=0){
		$SQL = "SELECT table_id
			FROM nds_table
			WHERE project_id NOT IN (
				SELECT project_id 
				FROM nds_table 
				WHERE table_id=$table_id)";
		$query = $this->db->query($SQL);
		$table_id = array();
		foreach($query->result() as $row){
			$table_id[] = $row->table_id;
		}
		$this->cms_show_json($table_id);
	}
	
	// get restricted table sibling
	public function get_restricted_column($table_id=0){
		$SQL = "SELECT column_id
			FROM nds_column
			WHERE 
				(table_id<>$table_id) OR
				(role='detail many to many') OR
				(role='detail one to many')";
		$query = $this->db->query($SQL);
		$column_id = array();
		foreach($query->result() as $row){
			$column_id[] = $row->column_id;
		}
		$this->cms_show_json($column_id);
	}
	
	
}
?>