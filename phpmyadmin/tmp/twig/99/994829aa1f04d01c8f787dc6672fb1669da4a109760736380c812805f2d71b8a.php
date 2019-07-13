<?php

/* server/databases/databases_header.twig */
class __TwigTemplate_a529475965e59951769cec5d151e52bb964ef4a74f7cdb8a85b13b699dd26bfc extends Twig_Template
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
        echo "<div id=\"tableslistcontainer\">
    ";
        // line 2
        echo PhpMyAdmin\Util::getListNavigator(        // line 3
(isset($context["database_count"]) ? $context["database_count"] : null),         // line 4
(isset($context["pos"]) ? $context["pos"] : null),         // line 5
(isset($context["url_params"]) ? $context["url_params"] : null), "server_databases.php", "frame_content",         // line 8
(isset($context["max_db_list"]) ? $context["max_db_list"] : null));
        // line 9
        echo "
    <form class=\"ajax\" action=\"server_databases.php\" method=\"post\" name=\"dbStatsForm\" id=\"dbStatsForm\">
        ";
        // line 11
        echo PhpMyAdmin\Url::getHiddenInputs((isset($context["url_params"]) ? $context["url_params"] : null));
        echo "
        ";
        // line 12
        $context["url_params"] = twig_array_merge((isset($context["url_params"]) ? $context["url_params"] : null), array("sort_by" => "SCHEMA_NAME", "sort_order" => ((((        // line 14
(isset($context["sort_by"]) ? $context["sort_by"] : null) == "SCHEMA_NAME") && ((isset($context["sort_order"]) ? $context["sort_order"] : null) == "asc"))) ? ("desc") : ("asc"))));
        // line 16
        echo "        <div class=\"responsivetable\">
            <table id=\"tabledatabases\" class=\"data\">
                ";
        // line 18
        $this->loadTemplate("server/databases/table_header.twig", "server/databases/databases_header.twig", 18)->display(array("url_params" =>         // line 19
(isset($context["url_params"]) ? $context["url_params"] : null), "sort_by" =>         // line 20
(isset($context["sort_by"]) ? $context["sort_by"] : null), "sort_order" =>         // line 21
(isset($context["sort_order"]) ? $context["sort_order"] : null), "sort_order_text" => (((        // line 22
(isset($context["sort_order"]) ? $context["sort_order"] : null) == "asc")) ? (_gettext("Ascending")) : (_gettext("Descending"))), "column_order" =>         // line 23
(isset($context["column_order"]) ? $context["column_order"] : null), "first_database" =>         // line 24
(isset($context["first_database"]) ? $context["first_database"] : null), "master_replication" =>         // line 25
(isset($context["master_replication"]) ? $context["master_replication"] : null), "slave_replication" =>         // line 26
(isset($context["slave_replication"]) ? $context["slave_replication"] : null), "is_superuser" =>         // line 27
(isset($context["is_superuser"]) ? $context["is_superuser"] : null), "allow_user_drop_database" =>         // line 28
(isset($context["allow_user_drop_database"]) ? $context["allow_user_drop_database"] : null)));
    }

    public function getTemplateName()
    {
        return "server/databases/databases_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 28,  52 => 27,  51 => 26,  50 => 25,  49 => 24,  48 => 23,  47 => 22,  46 => 21,  45 => 20,  44 => 19,  43 => 18,  39 => 16,  37 => 14,  36 => 12,  32 => 11,  28 => 9,  26 => 8,  25 => 5,  24 => 4,  23 => 3,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "server/databases/databases_header.twig", "/home/wwwroot/default/phpmyadmin/templates/server/databases/databases_header.twig");
    }
}
