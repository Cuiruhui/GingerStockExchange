<?php

/* server/databases/index.twig */
class __TwigTemplate_27281535c6aab9f849651e2bceb5472249343de4a5964710261118800498648e extends Twig_Template
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
        // line 2
        $this->loadTemplate("server/sub_page_header.twig", "server/databases/index.twig", 2)->display(array("type" => ((        // line 3
(isset($context["dbstats"]) ? $context["dbstats"] : null)) ? ("database_statistics") : ("databases"))));
        // line 5
        echo "
";
        // line 7
        if ((isset($context["show_create_db"]) ? $context["show_create_db"] : null)) {
            // line 8
            echo "    ";
            $this->loadTemplate("server/databases/create.twig", "server/databases/index.twig", 8)->display(array("is_create_db_priv" =>             // line 9
(isset($context["is_create_db_priv"]) ? $context["is_create_db_priv"] : null), "dbstats" =>             // line 10
(isset($context["dbstats"]) ? $context["dbstats"] : null), "db_to_create" =>             // line 11
(isset($context["db_to_create"]) ? $context["db_to_create"] : null), "server_collation" =>             // line 12
(isset($context["server_collation"]) ? $context["server_collation"] : null), "dbi" =>             // line 13
(isset($context["dbi"]) ? $context["dbi"] : null), "disable_is" =>             // line 14
(isset($context["disable_is"]) ? $context["disable_is"] : null)));
        }
        // line 17
        echo "
";
        // line 18
        $this->loadTemplate("filter.twig", "server/databases/index.twig", 18)->display(array("filter_value" => ""));
        // line 19
        echo "
";
        // line 21
        if ( !(null === (isset($context["databases"]) ? $context["databases"] : null))) {
            // line 22
            echo "    ";
            echo (isset($context["databases"]) ? $context["databases"] : null);
            echo "
";
        } else {
            // line 24
            echo "    <p>";
            echo _gettext("No databases");
            echo "</p>
";
        }
    }

    public function getTemplateName()
    {
        return "server/databases/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 24,  47 => 22,  45 => 21,  42 => 19,  40 => 18,  37 => 17,  34 => 14,  33 => 13,  32 => 12,  31 => 11,  30 => 10,  29 => 9,  27 => 8,  25 => 7,  22 => 5,  20 => 3,  19 => 2,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "server/databases/index.twig", "/home/wwwroot/default/phpmyadmin/templates/server/databases/index.twig");
    }
}
