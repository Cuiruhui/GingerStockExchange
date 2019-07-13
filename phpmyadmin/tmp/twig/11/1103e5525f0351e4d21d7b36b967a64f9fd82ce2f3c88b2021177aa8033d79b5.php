<?php

/* columns_definitions/column_definitions_form.twig */
class __TwigTemplate_1cd44b193d97575647c90e8784a52a5a42216c9697e7250035c1880230ec7e76 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<form method=\"post\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["action"]) ? $context["action"] : null), "html", null, true);
        echo "\" class=\"";
        // line 2
        echo ((((isset($context["action"]) ? $context["action"] : null) == "tbl_create.php")) ? ("create_table") : ("append_fields"));
        // line 3
        echo "_form ajax lock-page\">
    ";
        // line 4
        echo PhpMyAdmin\Url::getHiddenInputs((isset($context["form_params"]) ? $context["form_params"] : null));
        echo "
    ";
        // line 6
        echo "    ";
        // line 7
        echo "    ";
        // line 8
        echo "    <input type=\"hidden\" name=\"primary_indexes\" value=\"";
        // line 9
        echo twig_escape_filter($this->env, (( !twig_test_empty((isset($context["primary_indexes"]) ? $context["primary_indexes"] : null))) ? ((isset($context["primary_indexes"]) ? $context["primary_indexes"] : null)) : ("[]")), "html", null, true);
        echo "\">
    <input type=\"hidden\" name=\"unique_indexes\" value=\"";
        // line 11
        echo twig_escape_filter($this->env, (( !twig_test_empty((isset($context["unique_indexes"]) ? $context["unique_indexes"] : null))) ? ((isset($context["unique_indexes"]) ? $context["unique_indexes"] : null)) : ("[]")), "html", null, true);
        echo "\">
    <input type=\"hidden\" name=\"indexes\" value=\"";
        // line 13
        echo twig_escape_filter($this->env, (( !twig_test_empty((isset($context["indexes"]) ? $context["indexes"] : null))) ? ((isset($context["indexes"]) ? $context["indexes"] : null)) : ("[]")), "html", null, true);
        echo "\">
    <input type=\"hidden\" name=\"fulltext_indexes\" value=\"";
        // line 15
        echo twig_escape_filter($this->env, (( !twig_test_empty((isset($context["fulltext_indexes"]) ? $context["fulltext_indexes"] : null))) ? ((isset($context["fulltext_indexes"]) ? $context["fulltext_indexes"] : null)) : ("[]")), "html", null, true);
        echo "\">
    <input type=\"hidden\" name=\"spatial_indexes\" value=\"";
        // line 17
        echo twig_escape_filter($this->env, (( !twig_test_empty((isset($context["spatial_indexes"]) ? $context["spatial_indexes"] : null))) ? ((isset($context["spatial_indexes"]) ? $context["spatial_indexes"] : null)) : ("[]")), "html", null, true);
        echo "\">

    ";
        // line 19
        if (((isset($context["action"]) ? $context["action"] : null) == "tbl_create.php")) {
            // line 20
            echo "        <div id=\"table_name_col_no_outer\">
            <table id=\"table_name_col_no\" class=\"tdblock\">
                <tr class=\"vmiddle floatleft\">
                    <td>";
            // line 23
            echo _gettext("Table name");
            echo ":
                    <input type=\"text\"
                        name=\"table\"
                        size=\"40\"
                        maxlength=\"64\"
                        value=\"";
            // line 28
            echo twig_escape_filter($this->env, ((array_key_exists("table", $context)) ? ((isset($context["table"]) ? $context["table"] : null)) : ("")), "html", null, true);
            echo "\"
                        class=\"textfield\" autofocus required />
                    </td>
                    <td>
                        ";
            // line 32
            echo _gettext("Add");
            // line 33
            echo "                        <input type=\"number\"
                            id=\"added_fields\"
                            name=\"added_fields\"
                            size=\"2\"
                            value=\"1\"
                            min=\"1\"
                            onfocus=\"this.select()\" />
                        ";
            // line 40
            echo _gettext("column(s)");
            // line 41
            echo "                        <input type=\"button\"
                            name=\"submit_num_fields\"
                            value=\"";
            // line 43
            echo _gettext("Go");
            echo "\" />
                    </td>
                </tr>
            </table>
        </div>
    ";
        }
        // line 49
        echo "    ";
        if (twig_test_iterable((isset($context["content_cells"]) ? $context["content_cells"] : null))) {
            // line 50
            echo "        ";
            $this->loadTemplate("columns_definitions/table_fields_definitions.twig", "columns_definitions/column_definitions_form.twig", 50)->display(array("is_backup" =>             // line 51
(isset($context["is_backup"]) ? $context["is_backup"] : null), "fields_meta" =>             // line 52
(isset($context["fields_meta"]) ? $context["fields_meta"] : null), "mimework" =>             // line 53
(isset($context["mimework"]) ? $context["mimework"] : null), "content_cells" =>             // line 54
(isset($context["content_cells"]) ? $context["content_cells"] : null), "change_column" =>             // line 55
(isset($context["change_column"]) ? $context["change_column"] : null), "is_virtual_columns_supported" =>             // line 56
(isset($context["is_virtual_columns_supported"]) ? $context["is_virtual_columns_supported"] : null), "browse_mime" =>             // line 57
(isset($context["browse_mime"]) ? $context["browse_mime"] : null), "server_type" =>             // line 58
(isset($context["server_type"]) ? $context["server_type"] : null), "max_rows" =>             // line 59
(isset($context["max_rows"]) ? $context["max_rows"] : null), "char_editing" =>             // line 60
(isset($context["char_editing"]) ? $context["char_editing"] : null), "attribute_types" =>             // line 61
(isset($context["attribute_types"]) ? $context["attribute_types"] : null), "privs_available" =>             // line 62
(isset($context["privs_available"]) ? $context["privs_available"] : null), "max_length" =>             // line 63
(isset($context["max_length"]) ? $context["max_length"] : null), "dbi" =>             // line 64
(isset($context["dbi"]) ? $context["dbi"] : null), "disable_is" =>             // line 65
(isset($context["disable_is"]) ? $context["disable_is"] : null)));
            // line 67
            echo "    ";
        }
        // line 68
        echo "    ";
        if (((isset($context["action"]) ? $context["action"] : null) == "tbl_create.php")) {
            // line 69
            echo "        <div class=\"responsivetable\">
        <table>
            <tr class=\"vtop\">
                <th>";
            // line 72
            echo _gettext("Table comments:");
            echo "</th>
                <td width=\"25\">&nbsp;</td>
                <th>";
            // line 74
            echo _gettext("Collation:");
            echo "</th>
                <td width=\"25\">&nbsp;</td>
                <th>
                    ";
            // line 77
            echo _gettext("Storage Engine:");
            // line 78
            echo "                    ";
            echo PhpMyAdmin\Util::showMySQLDocu("Storage_engines");
            echo "
                </th>
                <td width=\"25\">&nbsp;</td>
                <th>
                    ";
            // line 82
            echo _gettext("Connection:");
            // line 83
            echo "                    ";
            echo PhpMyAdmin\Util::showMySQLDocu("federated-create-connection");
            echo "
                </th>
            </tr>
            <tr>
                <td>
                    <input type=\"text\"
                        name=\"comment\"
                        size=\"40\"
                        maxlength=\"60\"
                        value=\"";
            // line 92
            echo twig_escape_filter($this->env, ((array_key_exists("comment", $context)) ? ((isset($context["comment"]) ? $context["comment"] : null)) : ("")), "html", null, true);
            echo "\"
                        class=\"textfield\" />
                </td>
                <td width=\"25\">&nbsp;</td>
                <td>
                    ";
            // line 97
            echo PhpMyAdmin\Charsets::getCollationDropdownBox(            // line 98
(isset($context["dbi"]) ? $context["dbi"] : null),             // line 99
(isset($context["disable_is"]) ? $context["disable_is"] : null), "tbl_collation", null,             // line 102
(isset($context["tbl_collation"]) ? $context["tbl_collation"] : null), false);
            // line 104
            echo "
                </td>
                <td width=\"25\">&nbsp;</td>
                <td>
                    ";
            // line 108
            echo PhpMyAdmin\StorageEngine::getHtmlSelect("tbl_storage_engine", null,             // line 111
(isset($context["tbl_storage_engine"]) ? $context["tbl_storage_engine"] : null));
            // line 112
            echo "
                </td>
                <td width=\"25\">&nbsp;</td>
                <td>
                    <input type=\"text\"
                        name=\"connection\"
                        size=\"40\"
                        value=\"";
            // line 119
            echo twig_escape_filter($this->env, ((array_key_exists("connection", $context)) ? ((isset($context["connection"]) ? $context["connection"] : null)) : ("")), "html", null, true);
            echo "\"
                        placeholder=\"scheme://user_name[:password]@host_name[:port_num]/db_name/tbl_name\"
                        class=\"textfield\"
                        required=\"required\" />
                </td>
            </tr>
            ";
            // line 125
            if ((isset($context["have_partitioning"]) ? $context["have_partitioning"] : null)) {
                // line 126
                echo "                <tr class=\"vtop\">
                    <th colspan=\"5\">
                        ";
                // line 128
                echo _gettext("PARTITION definition:");
                // line 129
                echo "                        ";
                echo PhpMyAdmin\Util::showMySQLDocu("Partitioning");
                echo "
                    </th>
                </tr>
                <tr>
                    <td colspan=\"5\">
                        ";
                // line 134
                $this->loadTemplate("columns_definitions/partitions.twig", "columns_definitions/column_definitions_form.twig", 134)->display(array("partition_details" =>                 // line 135
(isset($context["partition_details"]) ? $context["partition_details"] : null)));
                // line 137
                echo "                    </td>
                </tr>
            ";
            }
            // line 140
            echo "        </table>
        </div>
    ";
        }
        // line 143
        echo "    <fieldset class=\"tblFooters\">
        <input type=\"button\"
            class=\"preview_sql\"
            value=\"";
        // line 146
        echo _gettext("Preview SQL");
        echo "\" />
        <input type=\"submit\"
            name=\"do_save_data\"
            value=\"";
        // line 149
        echo _gettext("Save");
        echo "\" />
    </fieldset>
    <div id=\"properties_message\"></div>
</form>
";
    }

    public function getTemplateName()
    {
        return "columns_definitions/column_definitions_form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  256 => 149,  250 => 146,  245 => 143,  240 => 140,  235 => 137,  233 => 135,  232 => 134,  223 => 129,  221 => 128,  217 => 126,  215 => 125,  206 => 119,  197 => 112,  195 => 111,  194 => 108,  188 => 104,  186 => 102,  185 => 99,  184 => 98,  183 => 97,  175 => 92,  162 => 83,  160 => 82,  152 => 78,  150 => 77,  144 => 74,  139 => 72,  134 => 69,  131 => 68,  128 => 67,  126 => 65,  125 => 64,  124 => 63,  123 => 62,  122 => 61,  121 => 60,  120 => 59,  119 => 58,  118 => 57,  117 => 56,  116 => 55,  115 => 54,  114 => 53,  113 => 52,  112 => 51,  110 => 50,  107 => 49,  98 => 43,  94 => 41,  92 => 40,  83 => 33,  81 => 32,  74 => 28,  66 => 23,  61 => 20,  59 => 19,  54 => 17,  50 => 15,  46 => 13,  42 => 11,  38 => 9,  36 => 8,  34 => 7,  32 => 6,  28 => 4,  25 => 3,  23 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "columns_definitions/column_definitions_form.twig", "/home/wwwroot/default/phpmyadmin/templates/columns_definitions/column_definitions_form.twig");
    }
}
