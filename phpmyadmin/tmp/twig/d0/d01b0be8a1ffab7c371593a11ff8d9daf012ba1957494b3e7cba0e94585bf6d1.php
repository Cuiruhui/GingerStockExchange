<?php

/* server/databases/table_header.twig */
class __TwigTemplate_70e4968101e01b26346cad38e985d20e2ce4f6db3f0c32ebdbf0febb48cc7bd1 extends Twig_Template
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
        echo "<thead>
    <tr>
        ";
        // line 3
        if (((isset($context["is_superuser"]) ? $context["is_superuser"] : null) || (isset($context["allow_user_drop_database"]) ? $context["allow_user_drop_database"] : null))) {
            // line 4
            echo "            <th></th>
        ";
        }
        // line 6
        echo "        <th>
            <a href=\"server_databases.php";
        // line 7
        echo PhpMyAdmin\Url::getCommon((isset($context["url_params"]) ? $context["url_params"] : null));
        echo "\">
                ";
        // line 8
        echo _gettext("Database");
        // line 9
        echo "                ";
        echo ((((isset($context["sort_by"]) ? $context["sort_by"] : null) == "SCHEMA_NAME")) ? (PhpMyAdmin\Util::getImage(("s_" .         // line 10
(isset($context["sort_order"]) ? $context["sort_order"] : null)),         // line 11
(isset($context["sort_order_text"]) ? $context["sort_order_text"] : null))) : (""));
        // line 12
        echo "
            </a>
        </th>
        ";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["column_order"]) ? $context["column_order"] : null));
        foreach ($context['_seq'] as $context["stat_name"] => $context["stat"]) {
            if (twig_in_filter($context["stat_name"], twig_get_array_keys_filter((isset($context["first_database"]) ? $context["first_database"] : null)))) {
                // line 16
                echo "            ";
                $context["url_params"] = twig_array_merge((isset($context["url_params"]) ? $context["url_params"] : null), array("sort_by" =>                 // line 17
$context["stat_name"], "sort_order" => ((((                // line 18
(isset($context["sort_by"]) ? $context["sort_by"] : null) == $context["stat_name"]) && ((isset($context["sort_order"]) ? $context["sort_order"] : null) == "desc"))) ? ("asc") : ("desc"))));
                // line 20
                echo "
            <th";
                // line 21
                echo ((($this->getAttribute($context["stat"], "format", array(), "array") === "byte")) ? (" colspan=\"2\"") : (""));
                echo ">
                <a href=\"server_databases.php";
                // line 22
                echo PhpMyAdmin\Url::getCommon((isset($context["url_params"]) ? $context["url_params"] : null));
                echo "\">
                    ";
                // line 23
                echo twig_escape_filter($this->env, $this->getAttribute($context["stat"], "disp_name", array(), "array"), "html", null, true);
                echo "
                    ";
                // line 24
                echo ((((isset($context["sort_by"]) ? $context["sort_by"] : null) == $context["stat_name"])) ? (PhpMyAdmin\Util::getImage(("s_" .                 // line 25
(isset($context["sort_order"]) ? $context["sort_order"] : null)),                 // line 26
(isset($context["sort_order_text"]) ? $context["sort_order_text"] : null))) : (""));
                // line 27
                echo "
                </a>
            </th>
        ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['stat_name'], $context['stat'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        echo "        ";
        if ((isset($context["master_replication"]) ? $context["master_replication"] : null)) {
            // line 32
            echo "            <th>";
            echo _gettext("Master replication");
            echo "</th>
        ";
        }
        // line 34
        echo "        ";
        if ((isset($context["slave_replication"]) ? $context["slave_replication"] : null)) {
            // line 35
            echo "            <th>";
            echo _gettext("Slave replication");
            echo "</th>
        ";
        }
        // line 37
        echo "        <th>";
        echo _gettext("Action");
        echo "</th>
    </tr>
</thead>
";
    }

    public function getTemplateName()
    {
        return "server/databases/table_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  105 => 37,  99 => 35,  96 => 34,  90 => 32,  87 => 31,  77 => 27,  75 => 26,  74 => 25,  73 => 24,  69 => 23,  65 => 22,  61 => 21,  58 => 20,  56 => 18,  55 => 17,  53 => 16,  48 => 15,  43 => 12,  41 => 11,  40 => 10,  38 => 9,  36 => 8,  32 => 7,  29 => 6,  25 => 4,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "server/databases/table_header.twig", "/home/wwwroot/default/phpmyadmin/templates/server/databases/table_header.twig");
    }
}
