<?php

/* database/structure/favorite_anchor.twig */
class __TwigTemplate_c78d0b598e2ed8b221b4742df7eca10ade732eb170e1f45373d0c4b5e420daf7 extends Twig_Template
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
        echo "<a id=\"";
        echo twig_escape_filter($this->env, (isset($context["table_name_hash"]) ? $context["table_name_hash"] : null), "html", null, true);
        echo "_favorite_anchor\"
    class=\"ajax favorite_table_anchor\"
    href=\"db_structure.php";
        // line 3
        echo PhpMyAdmin\Url::getCommon((isset($context["fav_params"]) ? $context["fav_params"] : null));
        echo "\"
    title=\"";
        // line 4
        echo twig_escape_filter($this->env, (((isset($context["already_favorite"]) ? $context["already_favorite"] : null)) ? (_gettext("Remove from Favorites")) : (_gettext("Add to Favorites"))), "html", null, true);
        echo "\"
    data-favtargets=\"";
        // line 5
        echo twig_escape_filter($this->env, (isset($context["db_table_name_hash"]) ? $context["db_table_name_hash"] : null), "html", null, true);
        echo "\" >
    ";
        // line 6
        echo (((isset($context["already_favorite"]) ? $context["already_favorite"] : null)) ? ($this->getAttribute((isset($context["titles"]) ? $context["titles"] : null), "Favorite", array(), "array")) : ($this->getAttribute((isset($context["titles"]) ? $context["titles"] : null), "NoFavorite", array(), "array")));
        echo "
</a>
";
    }

    public function getTemplateName()
    {
        return "database/structure/favorite_anchor.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 6,  33 => 5,  29 => 4,  25 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "database/structure/favorite_anchor.twig", "/home/wwwroot/default/phpmyadmin/templates/database/structure/favorite_anchor.twig");
    }
}
