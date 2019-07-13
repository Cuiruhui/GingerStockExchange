<?php

/* table/search/rows_normal.twig */
class __TwigTemplate_0099f6b6fc7957c73f5135c15790b0de2447e4f425a65dcce2e9882038a8dd67 extends Twig_Template
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
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(0, (twig_length_filter($this->env, (isset($context["column_names"]) ? $context["column_names"] : null)) - 1)));
        foreach ($context['_seq'] as $context["_key"] => $context["column_index"]) {
            // line 2
            echo "    <tr class=\"noclick\">
        ";
            // line 4
            echo "        ";
            if ((isset($context["geom_column_flag"]) ? $context["geom_column_flag"] : null)) {
                // line 5
                echo "            ";
                $this->loadTemplate("table/search/geom_func.twig", "table/search/rows_normal.twig", 5)->display(array("column_index" =>                 // line 6
$context["column_index"], "column_types" =>                 // line 7
(isset($context["column_types"]) ? $context["column_types"] : null)));
                // line 9
                echo "        ";
            }
            // line 10
            echo "        ";
            // line 11
            echo "        <th>
            ";
            // line 12
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["column_names"]) ? $context["column_names"] : null), $context["column_index"], array(), "array"), "html", null, true);
            echo "
        </th>
        ";
            // line 14
            $context["properties"] = $this->getAttribute((isset($context["self"]) ? $context["self"] : null), "getColumnProperties", array(0 => $context["column_index"], 1 => $context["column_index"]), "method");
            // line 15
            echo "        <td dir=\"ltr\">
            ";
            // line 16
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "type", array(), "array"), "html", null, true);
            echo "
        </td>
        <td>
            ";
            // line 19
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "collation", array(), "array"), "html", null, true);
            echo "
        </td>
        <td>
            ";
            // line 22
            echo $this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "func", array(), "array");
            echo "
        </td>
        ";
            // line 25
            echo "        <td data-type=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "type", array(), "array"), "html", null, true);
            echo "\">
            ";
            // line 26
            echo $this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "value", array(), "array");
            echo "
            ";
            // line 28
            echo "            <input type=\"hidden\"
                name=\"criteriaColumnNames[";
            // line 29
            echo twig_escape_filter($this->env, $context["column_index"], "html", null, true);
            echo "]\"
                value=\"";
            // line 30
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["column_names"]) ? $context["column_names"] : null), $context["column_index"], array(), "array"), "html", null, true);
            echo "\" />
            <input type=\"hidden\"
                name=\"criteriaColumnTypes[";
            // line 32
            echo twig_escape_filter($this->env, $context["column_index"], "html", null, true);
            echo "]\"
                value=\"";
            // line 33
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["column_types"]) ? $context["column_types"] : null), $context["column_index"], array(), "array"), "html", null, true);
            echo "\" />
            <input type=\"hidden\"
                name=\"criteriaColumnCollations[";
            // line 35
            echo twig_escape_filter($this->env, $context["column_index"], "html", null, true);
            echo "]\"
                value=\"";
            // line 36
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["column_collations"]) ? $context["column_collations"] : null), $context["column_index"], array(), "array"), "html", null, true);
            echo "\" />
        </td>
    </tr>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column_index'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    public function getTemplateName()
    {
        return "table/search/rows_normal.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  103 => 36,  99 => 35,  94 => 33,  90 => 32,  85 => 30,  81 => 29,  78 => 28,  74 => 26,  69 => 25,  64 => 22,  58 => 19,  52 => 16,  49 => 15,  47 => 14,  42 => 12,  39 => 11,  37 => 10,  34 => 9,  32 => 7,  31 => 6,  29 => 5,  26 => 4,  23 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "table/search/rows_normal.twig", "/home/wwwroot/default/phpmyadmin/templates/table/search/rows_normal.twig");
    }
}
