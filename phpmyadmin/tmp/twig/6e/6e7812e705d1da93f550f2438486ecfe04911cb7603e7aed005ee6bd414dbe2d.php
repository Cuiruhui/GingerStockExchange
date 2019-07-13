<?php

/* table/search/selection_form.twig */
class __TwigTemplate_42a6dbae97cac61ad92b6d7524034f3af1691da4d54260a645e0f456414e5657 extends Twig_Template
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
        if (((isset($context["search_type"]) ? $context["search_type"] : null) == "zoom")) {
            // line 2
            echo "    ";
            $this->loadTemplate("table/search/form_tag.twig", "table/search/selection_form.twig", 2)->display(array("script_name" => "tbl_zoom_select.php", "form_id" => "zoom_search_form", "db" =>             // line 5
(isset($context["db"]) ? $context["db"] : null), "table" =>             // line 6
(isset($context["table"]) ? $context["table"] : null), "goto" =>             // line 7
(isset($context["goto"]) ? $context["goto"] : null)));
            // line 9
            echo "    <fieldset id=\"fieldset_zoom_search\">
        <fieldset id=\"inputSection\">
            <legend>
                ";
            // line 12
            echo _gettext("Do a \"query by example\" (wildcard: \"%\") for two different columns");
            // line 13
            echo "            </legend>
            ";
            // line 14
            $this->loadTemplate("table/search/fields_table.twig", "table/search/selection_form.twig", 14)->display(array("self" =>             // line 15
(isset($context["self"]) ? $context["self"] : null), "search_type" =>             // line 16
(isset($context["search_type"]) ? $context["search_type"] : null), "geom_column_flag" =>             // line 17
(isset($context["geom_column_flag"]) ? $context["geom_column_flag"] : null), "column_names" =>             // line 18
(isset($context["column_names"]) ? $context["column_names"] : null), "column_types" =>             // line 19
(isset($context["column_types"]) ? $context["column_types"] : null), "column_collations" =>             // line 20
(isset($context["column_collations"]) ? $context["column_collations"] : null), "criteria_column_names" =>             // line 21
(isset($context["criteria_column_names"]) ? $context["criteria_column_names"] : null), "criteria_column_types" =>             // line 22
(isset($context["criteria_column_types"]) ? $context["criteria_column_types"] : null)));
            // line 24
            echo "            ";
            $this->loadTemplate("table/search/options_zoom.twig", "table/search/selection_form.twig", 24)->display(array("data_label" =>             // line 25
(isset($context["data_label"]) ? $context["data_label"] : null), "column_names" =>             // line 26
(isset($context["column_names"]) ? $context["column_names"] : null), "max_plot_limit" =>             // line 27
(isset($context["max_plot_limit"]) ? $context["max_plot_limit"] : null)));
            // line 29
            echo "        </fieldset>
    </fieldset>
";
        } elseif ((        // line 31
(isset($context["search_type"]) ? $context["search_type"] : null) == "normal")) {
            // line 32
            echo "    ";
            $this->loadTemplate("table/search/form_tag.twig", "table/search/selection_form.twig", 32)->display(array("script_name" => "tbl_select.php", "form_id" => "tbl_search_form", "db" =>             // line 35
(isset($context["db"]) ? $context["db"] : null), "table" =>             // line 36
(isset($context["table"]) ? $context["table"] : null), "goto" =>             // line 37
(isset($context["goto"]) ? $context["goto"] : null)));
            // line 39
            echo "    <fieldset id=\"fieldset_table_search\">
        <fieldset id=\"fieldset_table_qbe\">
            <legend>
                ";
            // line 42
            echo _gettext("Do a \"query by example\" (wildcard: \"%\")");
            // line 43
            echo "            </legend>
            <div class=\"responsivetable jsresponsive\">
                ";
            // line 45
            $this->loadTemplate("table/search/fields_table.twig", "table/search/selection_form.twig", 45)->display(array("self" =>             // line 46
(isset($context["self"]) ? $context["self"] : null), "search_type" =>             // line 47
(isset($context["search_type"]) ? $context["search_type"] : null), "geom_column_flag" =>             // line 48
(isset($context["geom_column_flag"]) ? $context["geom_column_flag"] : null), "column_names" =>             // line 49
(isset($context["column_names"]) ? $context["column_names"] : null), "column_types" =>             // line 50
(isset($context["column_types"]) ? $context["column_types"] : null), "column_collations" =>             // line 51
(isset($context["column_collations"]) ? $context["column_collations"] : null), "criteria_column_names" =>             // line 52
(isset($context["criteria_column_names"]) ? $context["criteria_column_names"] : null), "criteria_column_types" =>             // line 53
(isset($context["criteria_column_types"]) ? $context["criteria_column_types"] : null)));
            // line 55
            echo "            </div>
            <div id=\"gis_editor\"></div>
            <div id=\"popup_background\"></div>
        </fieldset>
        ";
            // line 59
            $this->loadTemplate("table/search/options.twig", "table/search/selection_form.twig", 59)->display(array("column_names" =>             // line 60
(isset($context["column_names"]) ? $context["column_names"] : null), "max_rows" =>             // line 61
(isset($context["max_rows"]) ? $context["max_rows"] : null)));
            // line 63
            echo "    </fieldset>
";
        } elseif ((        // line 64
(isset($context["search_type"]) ? $context["search_type"] : null) == "replace")) {
            // line 65
            echo "    ";
            $this->loadTemplate("table/search/form_tag.twig", "table/search/selection_form.twig", 65)->display(array("script_name" => "tbl_find_replace.php", "form_id" => "find_replace_form", "db" =>             // line 68
(isset($context["db"]) ? $context["db"] : null), "table" =>             // line 69
(isset($context["table"]) ? $context["table"] : null), "goto" =>             // line 70
(isset($context["goto"]) ? $context["goto"] : null)));
            // line 72
            echo "    <fieldset id=\"fieldset_find_replace\">
        <fieldset id=\"fieldset_find\">
            <legend>
                ";
            // line 75
            echo _gettext("Find and replace");
            // line 76
            echo "            </legend>
            ";
            // line 77
            $this->loadTemplate("table/search/search_and_replace.twig", "table/search/selection_form.twig", 77)->display(array("column_names" =>             // line 78
(isset($context["column_names"]) ? $context["column_names"] : null), "column_types" =>             // line 79
(isset($context["column_types"]) ? $context["column_types"] : null), "sql_types" =>             // line 80
(isset($context["sql_types"]) ? $context["sql_types"] : null)));
            // line 82
            echo "        </fieldset>
    </fieldset>
";
        } else {
            // line 85
            echo "    ";
            $this->loadTemplate("table/search/form_tag.twig", "table/search/selection_form.twig", 85)->display(array("script_name" => "", "form_id" => "", "db" =>             // line 88
(isset($context["db"]) ? $context["db"] : null), "table" =>             // line 89
(isset($context["table"]) ? $context["table"] : null), "goto" =>             // line 90
(isset($context["goto"]) ? $context["goto"] : null)));
        }
        // line 93
        echo "
";
        // line 95
        echo "    <fieldset class=\"tblFooters\">
        <input type=\"submit\"
            name=\"";
        // line 97
        echo ((((isset($context["search_type"]) ? $context["search_type"] : null) == "zoom")) ? ("zoom_submit") : ("submit"));
        echo "\"
            ";
        // line 98
        echo ((((isset($context["search_type"]) ? $context["search_type"] : null) == "zoom")) ? ("id=\"inputFormSubmitId\"") : (""));
        echo "
            value=\"";
        // line 99
        echo _gettext("Go");
        echo "\" />
    </fieldset>
</form>
<div id=\"sqlqueryresultsouter\"></div>
";
    }

    public function getTemplateName()
    {
        return "table/search/selection_form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  149 => 99,  145 => 98,  141 => 97,  137 => 95,  134 => 93,  131 => 90,  130 => 89,  129 => 88,  127 => 85,  122 => 82,  120 => 80,  119 => 79,  118 => 78,  117 => 77,  114 => 76,  112 => 75,  107 => 72,  105 => 70,  104 => 69,  103 => 68,  101 => 65,  99 => 64,  96 => 63,  94 => 61,  93 => 60,  92 => 59,  86 => 55,  84 => 53,  83 => 52,  82 => 51,  81 => 50,  80 => 49,  79 => 48,  78 => 47,  77 => 46,  76 => 45,  72 => 43,  70 => 42,  65 => 39,  63 => 37,  62 => 36,  61 => 35,  59 => 32,  57 => 31,  53 => 29,  51 => 27,  50 => 26,  49 => 25,  47 => 24,  45 => 22,  44 => 21,  43 => 20,  42 => 19,  41 => 18,  40 => 17,  39 => 16,  38 => 15,  37 => 14,  34 => 13,  32 => 12,  27 => 9,  25 => 7,  24 => 6,  23 => 5,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "table/search/selection_form.twig", "/home/wwwroot/default/phpmyadmin/templates/table/search/selection_form.twig");
    }
}
