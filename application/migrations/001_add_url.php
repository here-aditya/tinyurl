<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_url extends CI_Migration 
{

        public function up()
        {
                /*      Create master URL table
                        Store Unique URLs & short codes for both way transformation
                */
                 $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INTEGER',
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'main_url' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                        ),
                        'unique_code' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '6',
                                'null' => TRUE,
                        ),
                        'created_date' => array(
                                'type' => 'INTEGER',
                                'unsigned' => TRUE
                        )
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->add_key('unique_code');
                $attributes = array('ENGINE' => 'InnoDB');
                $this->dbforge->create_table('tiny_urls', TRUE, $attributes);

                /*      Create reference table for access count
                        Store no of times a URL is accessed through tiny URL
                */
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INTEGER',
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'url_refid' => array(
                                'type' => 'INTEGER',
                                'unsigned' => TRUE
                        ),
                        'counter' => array(
                                'type' => 'INTEGER',
                                'unsigned' => TRUE
                        ),
                        'created_date' => array(
                                'type' => 'INTEGER',
                                'unsigned' => TRUE
                        )
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (url_refid) REFERENCES tiny_urls(id)');
                $attributes = array('ENGINE' => 'InnoDB');
                $this->dbforge->create_table('url_counters', TRUE, $attributes);
        }

        public function down()
        {
                $this->dbforge->drop_table('blog');
        }
}
