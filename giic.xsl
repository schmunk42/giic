<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:x="http://www.w3.org/1999/xhtml"
                exclude-result-prefixes="x">
<xsl:output method="text" indent="yes" encoding="iso-8859-1"/>


<xsl:template match="/">
<xsl:apply-templates/>
-----
</xsl:template>

<xsl:template match="x:pre[@class='results']">
    <xsl:copy-of select="."/>
</xsl:template>

<xsl:template match="text()"/>
</xsl:stylesheet>