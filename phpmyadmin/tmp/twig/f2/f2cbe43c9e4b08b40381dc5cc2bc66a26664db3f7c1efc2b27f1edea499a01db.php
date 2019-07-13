<?php

/* database/structure/browse_table_label.twig */
class __TwigTemplate_9f1deac7081fdf761dbf0849281bfa5ed8f608aa78c4c7ed12f3a09dfc95a43b extends Twig_Template
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
        echo "<a href=\"sql.php";
        echo (isset($context["tbl_url_query"]) ? $context["tbl_url_query"] : null);
        echo "&amp;pos=0\" title=\"";
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "\">
    ";
        // line 2
        echo twig_escape_filter($this->env, (isset($context["truename"]) ? $context["truename"] : null), "html", null, true);
        echo "
</a>
";
    }

    public function getTemplateName()
    {
        return "database/structure/browse_table_label.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  26 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "database/structure/browse_table_label.twig", "/home/wwwroot/default/phpmyadmin/templates/database/structure/browse_table_label.twig");
    }
}
