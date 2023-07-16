<?php

namespace MediaWiki\Extension\PkgStore;

use MWException;
use OutputPage, Parser, PPFrame, Skin;

/**
 * Class MW_EXT_Spoiler
 */
class MW_EXT_Spoiler
{
  /**
   * Register tag function.
   *
   * @param Parser $parser
   *
   * @return void
   * @throws MWException
   */
  public static function onParserFirstCallInit(Parser $parser): void
  {
    $parser->setHook('spoiler', [__CLASS__, 'onRenderTagSpoiler']);
    $parser->setHook('hide', [__CLASS__, 'onRenderTagHide']);
  }

  /**
   * Render tag function: Spoiler.
   *
   * @param $input
   * @param array $args
   * @param Parser $parser
   * @param PPFrame $frame
   *
   * @return string
   */
  public static function onRenderTagSpoiler($input, array $args, Parser $parser, PPFrame $frame): string
  {
    // Argument: title.
    $getTitle = MW_EXT_Kernel::outClear($args['title'] ?? '' ?: '');
    $outTitle = empty($getTitle) ? MW_EXT_Kernel::getMessageText('spoiler', 'title') : $getTitle;

    // Get content.
    $getContent = trim($input);
    $outContent = $parser->recursiveTagParse($getContent, $frame);

    // Out HTML.
    $outHTML = '<details class="mw-spoiler navigation-not-searchable">';
    $outHTML .= '<summary>' . $outTitle . '</summary>';
    $outHTML .= '<div class="mw-spoiler-body"><div class="mw-spoiler-content">' . "\n\r" . $outContent . "\n\r" . '</div></div>';
    $outHTML .= '</details>';

    // Out parser.
    return $outHTML;
  }

  /**
   * Render tag function: Hide.
   *
   * @param $input
   * @param Parser $parser
   * @param PPFrame $frame
   *
   * @return string
   */
  public static function onRenderTagHide($input, Parser $parser, PPFrame $frame): string
  {
    // Get content.
    $getContent = trim($input);
    $outContent = $parser->recursiveTagParse($getContent, $frame);

    // Out HTML.
    $outHTML = '<span class="mw-hide navigation-not-searchable">';
    $outHTML .= '<span class="mw-hide-body"><span class="mw-hide-content">' . $outContent . '</span></span>';
    $outHTML .= '</span>';

    // Out parser.
    return $outHTML;
  }

  /**
   * Load resource function.
   *
   * @param OutputPage $out
   * @param Skin $skin
   *
   * @return void
   */
  public static function onBeforePageDisplay(OutputPage $out, Skin $skin): void
  {
    $out->addModuleStyles(['ext.mw.spoiler.styles']);
  }
}
