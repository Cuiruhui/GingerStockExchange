<?php

/* server/sub_page_header.twig */
class __TwigTemplate_4ef378478ba9c5ea6329dcf6f8f66346095ca398805efc922b87c134c07fa80f extends Twig_Template
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
        $context["header"] = array("variables" => array("image" => "s_vars", "text" => _gettext("Server variables and settings")), "engines" => array("image" => "b_engine", "text" => _gettext("Storage engines")), "plugins" => array("image" => "b_engine", "text" => _gettext("Plugins")), "binlog" => array("image" => "s_tbl", "text" => _gettext("Binary log")), "collations" => array("image" => "s_asci", "text" => _gettext("Character sets and collations")), "replication" => array("image" => "s_replication", "text" => _gettext("Replication")), "database_statistics" => array("image" => "s_db", "text" => _gettext("Databases statistics")), "databases" => array("image" => "s_db", "text" => _gettext("Databases")), "privileges" => array("image" => "b_usrlist", "text" => _gettext("Privileges")));
        // line 40
        echo "<h2>
    ";
        // line 41
        if (((array_key_exists("is_image", $context)) ? (_twig_default_filter((isset($context["is_image"]) ? $context["is_image"] : null), true)) : (true))) {
            // line 42
            echo "        ";
            echo PhpMyAdmin\Util::getImage($this->getAttribute($this->getAttribute((isset($context["header"]) ? $context["header"] : null), (isset($context["type"]) ? $context["type"] : null), array(), "array"), "image", array(), "array"));
            echo "
    ";
        } else {
            // line 44
            echo "        ";
            echo PhpMyAdmin\Util::getIcon($this->getAttribute($this->getAttribute((isset($context["header"]) ? $context["header"] : null), (isset($context["type"]) ? $context["type"] : null), array(), "array"), "image", array(), "array"));
            echo "
    ";
        }
        // line 46
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["header"]) ? $context["header"] : null), (isset($context["type"]) ? $context["type"] : null), array(), "array"), "text", array(), "array"), "html", null, true);
        echo "
    ";
        // line 47
        echo ((array_key_exists("link", $context)) ? (PhpMyAdmin\Util::showMySQLDocu((isset($context["link"]) ? $context["link"] : null))) : (""));
        echo "
</h2>
";
    }

    public function getTemplateName()
    {
        return "server/sub_page_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  43 => 47,  38 => 46,  32 => 44,  26 => 42,  24 => 41,  21 => 40,  19 => 2,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "server/sub_page_header.twig", "/home/wwwroot/default/phpmyadmin/templates/server/sub_page_header.twig");
    }
}
