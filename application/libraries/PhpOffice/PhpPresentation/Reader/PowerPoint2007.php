<?php
/**
 * This file is part of PHPPresentation - A pure PHP library for reading and writing
 * presentations documents.
 *
 * PHPPresentation is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPPresentation/contributors.
 *
 * @see        https://github.com/PHPOffice/PHPPresentation
 *
 * @copyright   2009-2015 PHPPresentation contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

declare(strict_types=1);

namespace PhpOffice\PhpPresentation\Reader;

use DateTime;
use DOMElement;
use DOMNode;
use DOMNodeList;
use PhpOffice\Common\Drawing as CommonDrawing;
use PhpOffice\Common\XMLReader;
use PhpOffice\PhpPresentation\DocumentLayout;
use PhpOffice\PhpPresentation\DocumentProperties;
use PhpOffice\PhpPresentation\Exception\FeatureNotImplementedException;
use PhpOffice\PhpPresentation\Exception\FileNotFoundException;
use PhpOffice\PhpPresentation\Exception\InvalidFileFormatException;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\PresentationProperties;
use PhpOffice\PhpPresentation\Shape\Drawing\Base64;
use PhpOffice\PhpPresentation\Shape\Drawing\Gd;
use PhpOffice\PhpPresentation\Shape\Hyperlink;
use PhpOffice\PhpPresentation\Shape\Placeholder;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Shape\RichText\Paragraph;
use PhpOffice\PhpPresentation\Shape\Table\Cell;
use PhpOffice\PhpPresentation\Slide;
use PhpOffice\PhpPresentation\Slide\AbstractSlide;
use PhpOffice\PhpPresentation\Slide\Note;
use PhpOffice\PhpPresentation\Slide\SlideLayout;
use PhpOffice\PhpPresentation\Slide\SlideMaster;
use PhpOffice\PhpPresentation\Style\Border;
use PhpOffice\PhpPresentation\Style\Borders;
use PhpOffice\PhpPresentation\Style\Bullet;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpPresentation\Style\Font;
use PhpOffice\PhpPresentation\Style\SchemeColor;
use PhpOffice\PhpPresentation\Style\TextStyle;
use ZipArchive;

/**
 * Serialized format reader.
 */
class PowerPoint2007 implements ReaderInterface
{
    /**
     * Output Object.
     *
     * @var PhpPresentation
     */
    protected $oPhpPresentation;
    /**
     * Output Object.
     *
     * @var \ZipArchive
     */
    protected $oZip;
    /**
     * @var array<string, array<string, array<string, string>>>
     */
    protected $arrayRels = [];
    /**
     * @var SlideLayout[]
     */
    protected $arraySlideLayouts = [];
    /**
     * @var string
     */
    protected $filename;
    /**
     * @var string
     */
    protected $fileRels;

    /**
     * Can the current \PhpOffice\PhpPresentation\Reader\ReaderInterface read the file?
     */
    public function canRead(string $pFilename): bool
    {
        return $this->fileSupportsUnserializePhpPresentation($pFilename);
    }

    /**
     * Does a file support UnserializePhpPresentation ?
     *
     * @throws FileNotFoundException
     */
    public function fileSupportsUnserializePhpPresentation(string $pFilename = ''): bool
    {
        // Check if file exists
        if (!file_exists($pFilename)) {
            throw new FileNotFoundException($pFilename);
        }

        $oZip = new ZipArchive();
        // Is it a zip ?
        if (true === $oZip->open($pFilename)) {
            // Is it an OpenXML Document ?
            // Is it a Presentation ?
            if (is_array($oZip->statName('[Content_Types].xml')) && is_array($oZip->statName('ppt/presentation.xml'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Loads PhpPresentation Serialized file.
     *
     * @throws InvalidFileFormatException
     */
    public function load(string $pFilename): PhpPresentation
    {
        // Unserialize... First make sure the file supports it!
        if (!$this->fileSupportsUnserializePhpPresentation($pFilename)) {
            throw new InvalidFileFormatException($pFilename, PowerPoint2007::class);
        }

        return $this->loadFile($pFilename);
    }

    /**
     * Load PhpPresentation Serialized file.
     */
    protected function loadFile(string $pFilename): PhpPresentation
    {
        $this->oPhpPresentation = new PhpPresentation();
        $this->oPhpPresentation->removeSlideByIndex();
        $this->oPhpPresentation->setAllMasterSlides([]);
        $this->filename = $pFilename;

        $this->oZip = new ZipArchive();
        $this->oZip->open($this->filename);
        $docPropsCore = $this->oZip->getFromName('docProps/core.xml');
        if (false !== $docPropsCore) {
            $this->loadDocumentProperties($docPropsCore);
        }

        $docPropsCustom = $this->oZip->getFromName('docProps/custom.xml');
        if (false !== $docPropsCustom) {
            $this->loadCustomProperties($docPropsCustom);
        }

        $pptViewProps = $this->oZip->getFromName('ppt/viewProps.xml');
        if (false !== $pptViewProps) {
            $this->loadViewProperties($pptViewProps);
        }

        $pptPresentation = $this->oZip->getFromName('ppt/presentation.xml');
        if (false !== $pptPresentation) {
            $this->loadDocumentLayout($pptPresentation);
            $this->loadSlides($pptPresentation);
        }

        $pptPresProps = $this->oZip->getFromName('ppt/presProps.xml');
        if (false !== $pptPresProps) {
            $this->loadPresentationProperties($pptPresentation);
        }

        return $this->oPhpPresentation;
    }

    /**
     * Read Document Layout.
     */
    protected function loadDocumentLayout(string $sPart): void
    {
        $xmlReader = new XMLReader();
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            foreach ($xmlReader->getElements('/p:presentation/p:sldSz') as $oElement) {
                if (!($oElement instanceof DOMElement)) {
                    continue;
                }
                $type = $oElement->getAttribute('type');
                $oLayout = $this->oPhpPresentation->getLayout();
                if (DocumentLayout::LAYOUT_CUSTOM == $type) {
                    $oLayout->setCX((float) $oElement->getAttribute('cx'));
                    $oLayout->setCY((float) $oElement->getAttribute('cy'));
                } else {
                    $oLayout->setDocumentLayout($type, true);
                    if ($oElement->getAttribute('cx') < $oElement->getAttribute('cy')) {
                        $oLayout->setDocumentLayout($type, false);
                    }
                }
            }
        }
    }

    /**
     * Read Document Properties.
     */
    protected function loadDocumentProperties(string $sPart): void
    {
        $xmlReader = new XMLReader();
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            $arrayProperties = [
                '/cp:coreProperties/dc:creator' => 'setCreator',
                '/cp:coreProperties/cp:lastModifiedBy' => 'setLastModifiedBy',
                '/cp:coreProperties/dc:title' => 'setTitle',
                '/cp:coreProperties/dc:description' => 'setDescription',
                '/cp:coreProperties/dc:subject' => 'setSubject',
                '/cp:coreProperties/cp:keywords' => 'setKeywords',
                '/cp:coreProperties/cp:category' => 'setCategory',
                '/cp:coreProperties/dcterms:created' => 'setCreated',
                '/cp:coreProperties/dcterms:modified' => 'setModified',
            ];
            $oProperties = $this->oPhpPresentation->getDocumentProperties();
            foreach ($arrayProperties as $path => $property) {
                $oElement = $xmlReader->getElement($path);
                if ($oElement instanceof DOMElement) {
                    if ($oElement->hasAttribute('xsi:type') && 'dcterms:W3CDTF' == $oElement->getAttribute('xsi:type')) {
                        $dateTime = DateTime::createFromFormat(DateTime::W3C, $oElement->nodeValue);
                        $oProperties->{$property}($dateTime->getTimestamp());
                    } else {
                        $oProperties->{$property}($oElement->nodeValue);
                    }
                }
            }
        }
    }

    /**
     * Read Custom Properties.
     */
    protected function loadCustomProperties(string $sPart): void
    {
        $xmlReader = new XMLReader();
        $sPart = str_replace(' xmlns="http://schemas.openxmlformats.org/officeDocument/2006/custom-properties"', '', $sPart);
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            foreach ($xmlReader->getElements('/Properties/property[@fmtid="{D5CDD505-2E9C-101B-9397-08002B2CF9AE}"]') as $element) {
                if (!$element->hasAttribute('name')) {
                    continue;
                }
                $propertyName = $element->getAttribute('name');
                if ($propertyName == '_MarkAsFinal') {
                    $attributeElement = $xmlReader->getElement('vt:bool', $element);
                    if ($attributeElement && 'true' == $attributeElement->nodeValue) {
                        $this->oPhpPresentation->getPresentationProperties()->markAsFinal(true);
                    }
                } else {
                    $attributeTypeInt = $xmlReader->getElement('vt:i4', $element);
                    $attributeTypeFloat = $xmlReader->getElement('vt:r8', $element);
                    $attributeTypeBoolean = $xmlReader->getElement('vt:bool', $element);
                    $attributeTypeDate = $xmlReader->getElement('vt:filetime', $element);
                    $attributeTypeString = $xmlReader->getElement('vt:lpwstr', $element);

                    if ($attributeTypeInt) {
                        $propertyType = DocumentProperties::PROPERTY_TYPE_INTEGER;
                        $propertyValue = (int) $attributeTypeInt->nodeValue;
                    } elseif ($attributeTypeFloat) {
                        $propertyType = DocumentProperties::PROPERTY_TYPE_FLOAT;
                        $propertyValue = (float) $attributeTypeFloat->nodeValue;
                    } elseif ($attributeTypeBoolean) {
                        $propertyType = DocumentProperties::PROPERTY_TYPE_BOOLEAN;
                        $propertyValue = $attributeTypeBoolean->nodeValue == 'true' ? true : false;
                    } elseif ($attributeTypeDate) {
                        $propertyType = DocumentProperties::PROPERTY_TYPE_DATE;
                        $propertyValue = strtotime($attributeTypeDate->nodeValue);
                    } else {
                        $propertyType = DocumentProperties::PROPERTY_TYPE_STRING;
                        $propertyValue = $attributeTypeString->nodeValue;
                    }

                    $this->oPhpPresentation->getDocumentProperties()->setCustomProperty($propertyName, $propertyValue, $propertyType);
                }
            }
        }
    }

    /**
     * Read Presentation Properties
     */
    protected function loadPresentationProperties(string $sPart): void
    {
        $xmlReader = new XMLReader();
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            $element = $xmlReader->getElement('/p:presentationPr/p:showPr');
            if ($element instanceof DOMElement) {
                if ($element->hasAttribute('loop')) {
                    $this->oPhpPresentation->getPresentationProperties()->setLoopContinuouslyUntilEsc(
                        (bool) $element->getAttribute('loop')
                    );
                }
                if (null !== $xmlReader->getElement('p:present', $element)) {
                    $this->oPhpPresentation->getPresentationProperties()->setSlideshowType(
                        PresentationProperties::SLIDESHOW_TYPE_PRESENT
                    );
                }
                if (null !== $xmlReader->getElement('p:browse', $element)) {
                    $this->oPhpPresentation->getPresentationProperties()->setSlideshowType(
                        PresentationProperties::SLIDESHOW_TYPE_BROWSE
                    );
                }
                if (null !== $xmlReader->getElement('p:kiosk', $element)) {
                    $this->oPhpPresentation->getPresentationProperties()->setSlideshowType(
                        PresentationProperties::SLIDESHOW_TYPE_KIOSK
                    );
                }
            }
        }
    }

    /**
     * Read View Properties.
     */
    protected function loadViewProperties(string $sPart): void
    {
        $xmlReader = new XMLReader();
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            $pathZoom = '/p:viewPr/p:slideViewPr/p:cSldViewPr/p:cViewPr/p:scale/a:sx';
            $oElement = $xmlReader->getElement($pathZoom);
            if ($oElement instanceof DOMElement) {
                if ($oElement->hasAttribute('d') && $oElement->hasAttribute('n')) {
                    $this->oPhpPresentation->getPresentationProperties()->setZoom($oElement->getAttribute('n') / $oElement->getAttribute('d'));
                }
            }
        }
    }

    /**
     * Extract all slides
     */
    protected function loadSlides(string $sPart): void
    {
        $xmlReader = new XMLReader();
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            $fileRels = 'ppt/_rels/presentation.xml.rels';
            $this->loadRels($fileRels);
            // Load the Masterslides
            $this->loadMasterSlides($xmlReader, $fileRels);
            // Continue with loading the slides
            foreach ($xmlReader->getElements('/p:presentation/p:sldIdLst/p:sldId') as $oElement) {
                if (!($oElement instanceof DOMElement)) {
                    continue;
                }
                $rId = $oElement->getAttribute('r:id');
                $pathSlide = isset($this->arrayRels[$fileRels][$rId]) ? $this->arrayRels[$fileRels][$rId]['Target'] : '';
                if (!empty($pathSlide)) {
                    $pptSlide = $this->oZip->getFromName('ppt/' . $pathSlide);
                    if (false !== $pptSlide) {
                        $slideRels = 'ppt/slides/_rels/' . basename($pathSlide) . '.rels';
                        $this->loadRels($slideRels);
                        $this->loadSlide($pptSlide, basename($pathSlide));
                        foreach ($this->arrayRels[$slideRels] as $rel) {
                            if ('http://schemas.openxmlformats.org/officeDocument/2006/relationships/notesSlide' == $rel['Type']) {
                                $this->loadSlideNote(basename($rel['Target']), $this->oPhpPresentation->getActiveSlide());
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Extract all MasterSlides
     */
    protected function loadMasterSlides(XMLReader $xmlReader, string $fileRels): void
    {
        // Get all the MasterSlide Id's from the presentation.xml file
        foreach ($xmlReader->getElements('/p:presentation/p:sldMasterIdLst/p:sldMasterId') as $oElement) {
            if (!($oElement instanceof DOMElement)) {
                continue;
            }
            $rId = $oElement->getAttribute('r:id');
            // Get the path to the masterslide from the array with _rels files
            $pathMasterSlide = isset($this->arrayRels[$fileRels][$rId]) ?
                $this->arrayRels[$fileRels][$rId]['Target'] : '';
            if (!empty($pathMasterSlide)) {
                $pptMasterSlide = $this->oZip->getFromName('ppt/' . $pathMasterSlide);
                if (false !== $pptMasterSlide) {
                    $this->loadRels('ppt/slideMasters/_rels/' . basename($pathMasterSlide) . '.rels');
                    $this->loadMasterSlide($pptMasterSlide, basename($pathMasterSlide));
                }
            }
        }
    }

    /**
     * Extract data from slide
     */
    protected function loadSlide(string $sPart, string $baseFile): void
    {
        $xmlReader = new XMLReader();
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            // Core
            $oSlide = $this->oPhpPresentation->createSlide();
            $this->oPhpPresentation->setActiveSlideIndex($this->oPhpPresentation->getSlideCount() - 1);
            $oSlide->setRelsIndex('ppt/slides/_rels/' . $baseFile . '.rels');

            // Background
            $oElement = $xmlReader->getElement('/p:sld/p:cSld/p:bg/p:bgPr');
            if ($oElement instanceof DOMElement) {
                $oElementColor = $xmlReader->getElement('a:solidFill/a:srgbClr', $oElement);
                if ($oElementColor instanceof DOMElement) {
                    // Color
                    $oColor = new Color();
                    $oColor->setRGB($oElementColor->hasAttribute('val') ? $oElementColor->getAttribute('val') : null);
                    // Background
                    $oBackground = new Slide\Background\Color();
                    $oBackground->setColor($oColor);
                    // Slide Background
                    $oSlide = $this->oPhpPresentation->getActiveSlide();
                    $oSlide->setBackground($oBackground);
                }
                $oElementColor = $xmlReader->getElement('a:solidFill/a:schemeClr', $oElement);
                if ($oElementColor instanceof DOMElement) {
                    // Color
                    $oColor = new SchemeColor();
                    $oColor->setValue($oElementColor->hasAttribute('val') ? $oElementColor->getAttribute('val') : null);
                    // Background
                    $oBackground = new Slide\Background\SchemeColor();
                    $oBackground->setSchemeColor($oColor);
                    // Slide Background
                    $oSlide = $this->oPhpPresentation->getActiveSlide();
                    $oSlide->setBackground($oBackground);
                }
                $oElementImage = $xmlReader->getElement('a:blipFill/a:blip', $oElement);
                if ($oElementImage instanceof DOMElement) {
                    $relImg = $this->arrayRels['ppt/slides/_rels/' . $baseFile . '.rels'][$oElementImage->getAttribute('r:embed')];
                    if (is_array($relImg)) {
                        // File
                        $pathImage = 'ppt/slides/' . $relImg['Target'];
                        $pathImage = explode('/', $pathImage);
                        foreach ($pathImage as $key => $partPath) {
                            if ('..' == $partPath) {
                                unset($pathImage[$key - 1]);
                                unset($pathImage[$key]);
                            }
                        }
                        $pathImage = implode('/', $pathImage);
                        $contentImg = $this->oZip->getFromName($pathImage);

                        $tmpBkgImg = tempnam(sys_get_temp_dir(), 'PhpPresentationReaderPpt2007Bkg');
                        file_put_contents($tmpBkgImg, $contentImg);
                        // Background
                        $oBackground = new Slide\Background\Image();
                        $oBackground->setPath($tmpBkgImg);
                        // Slide Background
                        $oSlide = $this->oPhpPresentation->getActiveSlide();
                        $oSlide->setBackground($oBackground);
                    }
                }
            }

            // Shapes
            $arrayElements = $xmlReader->getElements('/p:sld/p:cSld/p:spTree/*');
            $this->loadSlideShapes($oSlide, $arrayElements, $xmlReader);

            // Layout
            $oSlide = $this->oPhpPresentation->getActiveSlide();
            foreach ($this->arrayRels['ppt/slides/_rels/' . $baseFile . '.rels'] as $valueRel) {
                if ('http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideLayout' == $valueRel['Type']) {
                    $layoutBasename = basename($valueRel['Target']);
                    if (array_key_exists($layoutBasename, $this->arraySlideLayouts)) {
                        $oSlide->setSlideLayout($this->arraySlideLayouts[$layoutBasename]);
                    }
                    break;
                }
            }
        }
    }

    protected function loadMasterSlide(string $sPart, string $baseFile): void
    {
        $xmlReader = new XMLReader();
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            // Core
            $oSlideMaster = $this->oPhpPresentation->createMasterSlide();
            $oSlideMaster->setTextStyles(new TextStyle(false));
            $oSlideMaster->setRelsIndex('ppt/slideMasters/_rels/' . $baseFile . '.rels');

            // Background
            $oElement = $xmlReader->getElement('/p:sldMaster/p:cSld/p:bg');
            if ($oElement instanceof DOMElement) {
                $this->loadSlideBackground($xmlReader, $oElement, $oSlideMaster);
            }

            // Shapes
            $arrayElements = $xmlReader->getElements('/p:sldMaster/p:cSld/p:spTree/*');
            $this->loadSlideShapes($oSlideMaster, $arrayElements, $xmlReader);

            // Header & Footer

            // ColorMapping
            $colorMap = [];
            $oElement = $xmlReader->getElement('/p:sldMaster/p:clrMap');
            if ($oElement->hasAttributes()) {
                foreach ($oElement->attributes as $attr) {
                    $colorMap[$attr->nodeName] = $attr->nodeValue;
                }
                $oSlideMaster->colorMap->setMapping($colorMap);
            }

            // TextStyles
            $arrayElementTxStyles = $xmlReader->getElements('/p:sldMaster/p:txStyles/*');
            foreach ($arrayElementTxStyles as $oElementTxStyle) {
                $arrayElementsLvl = $xmlReader->getElements('/p:sldMaster/p:txStyles/' . $oElementTxStyle->nodeName . '/*');
                foreach ($arrayElementsLvl as $oElementLvl) {
                    if (!($oElementLvl instanceof DOMElement) || 'a:extLst' == $oElementLvl->nodeName) {
                        continue;
                    }
                    $oRTParagraph = new Paragraph();

                    if ('a:defPPr' == $oElementLvl->nodeName) {
                        $level = 0;
                    } else {
                        $level = str_replace('a:lvl', '', $oElementLvl->nodeName);
                        $level = str_replace('pPr', '', $level);
                        $level = intval($level);
                    }

                    if ($oElementLvl->hasAttribute('algn')) {
                        $oRTParagraph->getAlignment()->setHorizontal($oElementLvl->getAttribute('algn'));
                    }
                    if ($oElementLvl->hasAttribute('marL')) {
                        $val = (int) $oElementLvl->getAttribute('marL');
                        $val = CommonDrawing::emuToPixels((int) $val);
                        $oRTParagraph->getAlignment()->setMarginLeft($val);
                    }
                    if ($oElementLvl->hasAttribute('marR')) {
                        $val = (int) $oElementLvl->getAttribute('marR');
                        $val = CommonDrawing::emuToPixels((int) $val);
                        $oRTParagraph->getAlignment()->setMarginRight($val);
                    }
                    if ($oElementLvl->hasAttribute('indent')) {
                        $val = (int) $oElementLvl->getAttribute('indent');
                        $val = CommonDrawing::emuToPixels((int) $val);
                        $oRTParagraph->getAlignment()->setIndent($val);
                    }
                    $oElementLvlDefRPR = $xmlReader->getElement('a:defRPr', $oElementLvl);
                    if ($oElementLvlDefRPR instanceof DOMElement) {
                        if ($oElementLvlDefRPR->hasAttribute('sz')) {
                            $oRTParagraph->getFont()->setSize($oElementLvlDefRPR->getAttribute('sz') / 100);
                        }
                        if ($oElementLvlDefRPR->hasAttribute('b') && 1 == $oElementLvlDefRPR->getAttribute('b')) {
                            $oRTParagraph->getFont()->setBold(true);
                        }
                        if ($oElementLvlDefRPR->hasAttribute('i') && 1 == $oElementLvlDefRPR->getAttribute('i')) {
                            $oRTParagraph->getFont()->setItalic(true);
                        }
                    }
                    $oElementSchemeColor = $xmlReader->getElement('a:defRPr/a:solidFill/a:schemeClr', $oElementLvl);
                    if ($oElementSchemeColor instanceof DOMElement) {
                        if ($oElementSchemeColor->hasAttribute('val')) {
                            $oSchemeColor = new SchemeColor();
                            $oSchemeColor->setValue($oElementSchemeColor->getAttribute('val'));
                            $oRTParagraph->getFont()->setColor($oSchemeColor);
                        }
                    }

                    switch ($oElementTxStyle->nodeName) {
                        case 'p:bodyStyle':
                            $oSlideMaster->getTextStyles()->setBodyStyleAtLvl($oRTParagraph, $level);
                            break;
                        case 'p:otherStyle':
                            $oSlideMaster->getTextStyles()->setOtherStyleAtLvl($oRTParagraph, $level);
                            break;
                        case 'p:titleStyle':
                            $oSlideMaster->getTextStyles()->setTitleStyleAtLvl($oRTParagraph, $level);
                            break;
                    }
                }
            }

            // Load the theme
            foreach ($this->arrayRels[$oSlideMaster->getRelsIndex()] as $arrayRel) {
                if ('http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme' == $arrayRel['Type']) {
                    $pptTheme = $this->oZip->getFromName('ppt/' . substr($arrayRel['Target'], strrpos($arrayRel['Target'], '../') + 3));
                    if (false !== $pptTheme) {
                        $this->loadTheme($pptTheme, $oSlideMaster);
                    }
                    break;
                }
            }

            // Load the Layoutslide
            foreach ($xmlReader->getElements('/p:sldMaster/p:sldLayoutIdLst/p:sldLayoutId') as $oElement) {
                if (!($oElement instanceof DOMElement)) {
                    continue;
                }
                $rId = $oElement->getAttribute('r:id');
                // Get the path to the masterslide from the array with _rels files
                $pathLayoutSlide = isset($this->arrayRels[$oSlideMaster->getRelsIndex()][$rId]) ?
                    $this->arrayRels[$oSlideMaster->getRelsIndex()][$rId]['Target'] : '';
                if (!empty($pathLayoutSlide)) {
                    $pptLayoutSlide = $this->oZip->getFromName('ppt/' . substr($pathLayoutSlide, strrpos($pathLayoutSlide, '../') + 3));
                    if (false !== $pptLayoutSlide) {
                        $this->loadRels('ppt/slideLayouts/_rels/' . basename($pathLayoutSlide) . '.rels');
                        $oSlideMaster->addSlideLayout(
                            $this->loadLayoutSlide($pptLayoutSlide, basename($pathLayoutSlide), $oSlideMaster)
                        );
                    }
                }
            }
        }
    }

    protected function loadLayoutSlide(string $sPart, string $baseFile, SlideMaster $oSlideMaster): ?SlideLayout
    {
        $xmlReader = new XMLReader();
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            // Core
            $oSlideLayout = new SlideLayout($oSlideMaster);
            $oSlideLayout->setRelsIndex('ppt/slideLayouts/_rels/' . $baseFile . '.rels');

            // Name
            $oElement = $xmlReader->getElement('/p:sldLayout/p:cSld');
            if ($oElement instanceof DOMElement && $oElement->hasAttribute('name')) {
                $oSlideLayout->setLayoutName($oElement->getAttribute('name'));
            }

            // Background
            $oElement = $xmlReader->getElement('/p:sldLayout/p:cSld/p:bg');
            if ($oElement instanceof DOMElement) {
                $this->loadSlideBackground($xmlReader, $oElement, $oSlideLayout);
            }

            // ColorMapping
            $oElement = $xmlReader->getElement('/p:sldLayout/p:clrMapOvr/a:overrideClrMapping');
            if ($oElement instanceof DOMElement && $oElement->hasAttributes()) {
                $colorMap = [];
                foreach ($oElement->attributes as $attr) {
                    $colorMap[$attr->nodeName] = $attr->nodeValue;
                }
                $oSlideLayout->colorMap->setMapping($colorMap);
            }

            // Shapes
            $oElements = $xmlReader->getElements('/p:sldLayout/p:cSld/p:spTree/*');
            $this->loadSlideShapes($oSlideLayout, $oElements, $xmlReader);
            $this->arraySlideLayouts[$baseFile] = &$oSlideLayout;

            return $oSlideLayout;
        }
        /* @phpstan-ignore-next-line */
        return null;
    }

    protected function loadTheme(string $sPart, SlideMaster $oSlideMaster): void
    {
        $xmlReader = new XMLReader();
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            $oElements = $xmlReader->getElements('/a:theme/a:themeElements/a:clrScheme/*');
            foreach ($oElements as $oElement) {
                if ($oElement instanceof DOMElement) {
                    $oSchemeColor = new SchemeColor();
                    $oSchemeColor->setValue(str_replace('a:', '', $oElement->tagName));
                    $colorElement = $xmlReader->getElement('*', $oElement);
                    if ($colorElement instanceof DOMElement) {
                        if ($colorElement->hasAttribute('lastClr')) {
                            $oSchemeColor->setRGB($colorElement->getAttribute('lastClr'));
                        } elseif ($colorElement->hasAttribute('val')) {
                            $oSchemeColor->setRGB($colorElement->getAttribute('val'));
                        }
                    }
                    $oSlideMaster->addSchemeColor($oSchemeColor);
                }
            }
        }
    }

    protected function loadSlideBackground(XMLReader $xmlReader, DOMElement $oElement, AbstractSlide $oSlide): void
    {
        // Background color
        $oElementColor = $xmlReader->getElement('p:bgPr/a:solidFill/a:srgbClr', $oElement);
        if ($oElementColor instanceof DOMElement) {
            // Color
            $oColor = new Color();
            $oColor->setRGB($oElementColor->hasAttribute('val') ? $oElementColor->getAttribute('val') : null);
            // Background
            $oBackground = new Slide\Background\Color();
            $oBackground->setColor($oColor);
            // Slide Background
            $oSlide->setBackground($oBackground);
        }

        // Background scheme color
        $oElementSchemeColor = $xmlReader->getElement('p:bgRef/a:schemeClr', $oElement);
        if ($oElementSchemeColor instanceof DOMElement) {
            // Color
            $oColor = new SchemeColor();
            $oColor->setValue($oElementSchemeColor->hasAttribute('val') ? $oElementSchemeColor->getAttribute('val') : null);
            // Background
            $oBackground = new Slide\Background\SchemeColor();
            $oBackground->setSchemeColor($oColor);
            // Slide Background
            $oSlide->setBackground($oBackground);
        }

        // Background image
        $oElementImage = $xmlReader->getElement('p:bgPr/a:blipFill/a:blip', $oElement);
        if ($oElementImage instanceof DOMElement) {
            $relImg = $this->arrayRels[$oSlide->getRelsIndex()][$oElementImage->getAttribute('r:embed')];
            if (is_array($relImg)) {
                // File
                $pathImage = 'ppt/slides/' . $relImg['Target'];
                $pathImage = explode('/', $pathImage);
                foreach ($pathImage as $key => $partPath) {
                    if ('..' == $partPath) {
                        unset($pathImage[$key - 1]);
                        unset($pathImage[$key]);
                    }
                }
                $pathImage = implode('/', $pathImage);
                $contentImg = $this->oZip->getFromName($pathImage);

                $tmpBkgImg = tempnam(sys_get_temp_dir(), 'PhpPresentationReaderPpt2007Bkg');
                file_put_contents($tmpBkgImg, $contentImg);
                // Background
                $oBackground = new Slide\Background\Image();
                $oBackground->setPath($tmpBkgImg);
                // Slide Background
                $oSlide->setBackground($oBackground);
            }
        }
    }

    protected function loadSlideNote(string $baseFile, Slide $oSlide): void
    {
        $sPart = $this->oZip->getFromName('ppt/notesSlides/' . $baseFile);
        $xmlReader = new XMLReader();
        /* @phpstan-ignore-next-line */
        if ($xmlReader->getDomFromString($sPart)) {
            $oNote = $oSlide->getNote();

            $arrayElements = $xmlReader->getElements('/p:notes/p:cSld/p:spTree/*');
            $this->loadSlideShapes($oNote, $arrayElements, $xmlReader);
        }
    }

    protected function loadShapeDrawing(XMLReader $document, DOMElement $node, AbstractSlide $oSlide): void
    {
        // Core
        $document->registerNamespace('asvg', 'http://schemas.microsoft.com/office/drawing/2016/SVG/main');
        if ($document->getElement('p:blipFill/a:blip/a:extLst/a:ext/asvg:svgBlip', $node)) {
            $oShape = new Base64();
        } else {
            $oShape = new Gd();
        }
        $oShape->getShadow()->setVisible(false);
        // Variables
        $fileRels = $oSlide->getRelsIndex();

        $oElement = $document->getElement('p:nvPicPr/p:cNvPr', $node);
        if ($oElement instanceof DOMElement) {
            $oShape->setName($oElement->hasAttribute('name') ? $oElement->getAttribute('name') : '');
            $oShape->setDescription($oElement->hasAttribute('descr') ? $oElement->getAttribute('descr') : '');

            // Hyperlink
            $oElementHlinkClick = $document->getElement('a:hlinkClick', $oElement);
            if (is_object($oElementHlinkClick)) {
                $oShape->setHyperlink(
                    $this->loadHyperlink($document, $oElementHlinkClick, $oShape->getHyperlink())
                );
            }
        }

        $oElement = $document->getElement('p:blipFill/a:blip', $node);
        if ($oElement instanceof DOMElement) {
            if ($oElement->hasAttribute('r:embed') && isset($this->arrayRels[$fileRels][$oElement->getAttribute('r:embed')]['Target'])) {
                $pathImage = 'ppt/slides/' . $this->arrayRels[$fileRels][$oElement->getAttribute('r:embed')]['Target'];
                $pathImage = explode('/', $pathImage);
                foreach ($pathImage as $key => $partPath) {
                    if ('..' == $partPath) {
                        unset($pathImage[$key - 1]);
                        unset($pathImage[$key]);
                    }
                }
                $pathImage = implode('/', $pathImage);
                $imageFile = $this->oZip->getFromName($pathImage);
                if (!empty($imageFile)) {
                    if ($oShape instanceof Gd) {
                        $info = getimagesizefromstring($imageFile);
                        $oShape->setMimeType($info['mime']);
                        $oShape->setRenderingFunction(str_replace('/', '', $info['mime']));
                        $oShape->setImageResource(imagecreatefromstring($imageFile));
                    } elseif ($oShape instanceof Base64) {
                        $oShape->setData('data:image/svg+xml;base64,' . base64_encode($imageFile));
                    }
                }
            }
        }

        $oElement = $document->getElement('p:spPr', $node);
        if ($oElement instanceof DOMElement) {
            $oFill = $this->loadStyleFill($document, $oElement);
            $oShape->setFill($oFill);
        }

        $oElement = $document->getElement('p:spPr/a:xfrm', $node);
        if ($oElement instanceof DOMElement) {
            if ($oElement->hasAttribute('rot')) {
                $oShape->setRotation((int) CommonDrawing::angleToDegrees((int) $oElement->getAttribute('rot')));
            }
        }

        $oElement = $document->getElement('p:spPr/a:xfrm/a:off', $node);
        if ($oElement instanceof DOMElement) {
            if ($oElement->hasAttribute('x')) {
                // $oShape->setOffsetX(CommonDrawing::emuToPixels((int) $oElement->getAttribute('x')));
                $oShape->setOffsetX((int)CommonDrawing::emuToPixels((int) $oElement->getAttribute('x')));
            }
            if ($oElement->hasAttribute('y')) {
                // $oShape->setOffsetY(CommonDrawing::emuToPixels((int) $oElement->getAttribute('y')));
                $oShape->setOffsetY((int)CommonDrawing::emuToPixels((int) $oElement->getAttribute('y')));
            }
        }

        $oElement = $document->getElement('p:spPr/a:xfrm/a:ext', $node);
        if ($oElement instanceof DOMElement) {
            if ($oElement->hasAttribute('cx')) {
                // $oShape->setWidth(CommonDrawing::emuToPixels((int) $oElement->getAttribute('cx')));
                $oShape->setWidth((int)CommonDrawing::emuToPixels((int) $oElement->getAttribute('cx')));
            }
            if ($oElement->hasAttribute('cy')) {
                // $oShape->setHeight(CommonDrawing::emuToPixels((int) $oElement->getAttribute('cy')));
                $oShape->setHeight((int)CommonDrawing::emuToPixels((int) $oElement->getAttribute('cy')));
            }
        }

        $oElement = $document->getElement('p:spPr/a:effectLst', $node);
        if ($oElement instanceof DOMElement) {
            $oShape->getShadow()->setVisible(true);

            $oSubElement = $document->getElement('a:outerShdw', $oElement);
            if ($oSubElement instanceof DOMElement) {
                if ($oSubElement->hasAttribute('blurRad')) {
                    // $oShape->getShadow()->setBlurRadius(CommonDrawing::emuToPixels((int) $oSubElement->getAttribute('blurRad')));
                    $oShape->getShadow()->setBlurRadius((int)CommonDrawing::emuToPixels((int) $oSubElement->getAttribute('blurRad')));
                }
                if ($oSubElement->hasAttribute('dist')) {
                    // $oShape->getShadow()->setDistance(CommonDrawing::emuToPixels((int) $oSubElement->getAttribute('dist')));
                    $oShape->getShadow()->setDistance((int)CommonDrawing::emuToPixels((int) $oSubElement->getAttribute('dist')));
                }
                if ($oSubElement->hasAttribute('dir')) {
                    $oShape->getShadow()->setDirection((int) CommonDrawing::angleToDegrees((int) $oSubElement->getAttribute('dir')));
                }
                if ($oSubElement->hasAttribute('algn')) {
                    $oShape->getShadow()->setAlignment($oSubElement->getAttribute('algn'));
                }
            }

            $oSubElement = $document->getElement('a:outerShdw/a:srgbClr', $oElement);
            if ($oSubElement instanceof DOMElement) {
                if ($oSubElement->hasAttribute('val')) {
                    $oColor = new Color();
                    $oColor->setRGB($oSubElement->getAttribute('val'));
                    $oShape->getShadow()->setColor($oColor);
                }
            }

            $oSubElement = $document->getElement('a:outerShdw/a:srgbClr/a:alpha', $oElement);
            if ($oSubElement instanceof DOMElement) {
                if ($oSubElement->hasAttribute('val')) {
                    $oShape->getShadow()->setAlpha((int) $oSubElement->getAttribute('val') / 1000);
                }
            }
        }

        $oSlide->addShape($oShape);
    }

    // protected function loadShapeRichText(XMLReader $document, DOMElement $node, AbstractSlide $oSlide): void
    protected function loadShapeRichText(XMLReader $document, DOMElement $node, $oSlide): void
    // protected function loadShapeRichText( $document,  $node,  $oSlide): void
    {
        // if (!$document->elementExists('p:txBody/a:p/a:r', $node)) {
        //     return;
        // }
        if (!$document->elementExists('p:txBody/a:p/a:r', $node) || !$oSlide instanceof AbstractSlide) {
            return;
        }
        // Core
        $oShape = $oSlide->createRichTextShape();
        $oShape->setParagraphs([]);
        // Variables
        if ($oSlide instanceof AbstractSlide) {
            $this->fileRels = $oSlide->getRelsIndex();
        }

        $oElement = $document->getElement('p:spPr/a:xfrm', $node);
        if ($oElement instanceof DOMElement && $oElement->hasAttribute('rot')) {
            $oShape->setRotation((int) CommonDrawing::angleToDegrees((int) $oElement->getAttribute('rot')));
        }

        $oElement = $document->getElement('p:spPr/a:xfrm/a:off', $node);
        if ($oElement instanceof DOMElement) {
            if ($oElement->hasAttribute('x')) {
                // $oShape->setOffsetX(CommonDrawing::emuToPixels((int) $oElement->getAttribute('x')));
                $oShape->setOffsetX((int)CommonDrawing::emuToPixels((int) $oElement->getAttribute('x')));
            }
            if ($oElement->hasAttribute('y')) {
                // $oShape->setOffsetY(CommonDrawing::emuToPixels((int) $oElement->getAttribute('y')));
                $oShape->setOffsetY((int)CommonDrawing::emuToPixels((int) $oElement->getAttribute('y')));
            }
        }

        $oElement = $document->getElement('p:spPr/a:xfrm/a:ext', $node);
        if ($oElement instanceof DOMElement) {
            if ($oElement->hasAttribute('cx')) {
                // $oShape->setWidth(CommonDrawing::emuToPixels((int) $oElement->getAttribute('cx')));
                $oShape->setWidth((int)CommonDrawing::emuToPixels((int) $oElement->getAttribute('cx')));
            }
            if ($oElement->hasAttribute('cy')) {
                // $oShape->setHeight(CommonDrawing::emuToPixels((int) $oElement->getAttribute('cy')));
                $oShape->setHeight((int)CommonDrawing::emuToPixels((int) $oElement->getAttribute('cy')));
            }
        }

        $oElement = $document->getElement('p:nvSpPr/p:nvPr/p:ph', $node);
        if ($oElement instanceof DOMElement) {
            if ($oElement->hasAttribute('type')) {
                $placeholder = new Placeholder($oElement->getAttribute('type'));
                $oShape->setPlaceHolder($placeholder);
            }
        }

        $arrayElements = $document->getElements('p:txBody/a:p', $node);
        foreach ($arrayElements as $oElement) {
            if ($oElement instanceof DOMElement) {
                $this->loadParagraph($document, $oElement, $oShape);
            }
        }

        if (count($oShape->getParagraphs()) > 0) {
            $oShape->setActiveParagraph(0);
        }
    }

    protected function loadShapeTable(XMLReader $document, DOMElement $node, AbstractSlide $oSlide): void
    {
        $this->fileRels = $oSlide->getRelsIndex();

        $oShape = $oSlide->createTableShape();

        $oElement = $document->getElement('p:cNvPr', $node);
        if ($oElement instanceof DOMElement) {
            if ($oElement->hasAttribute('name')) {
                $oShape->setName($oElement->getAttribute('name'));
            }
            if ($oElement->hasAttribute('descr')) {
                $oShape->setDescription($oElement->getAttribute('descr'));
            }
        }

        $oElement = $document->getElement('p:xfrm/a:off', $node);
        if ($oElement instanceof DOMElement) {
            if ($oElement->hasAttribute('x')) {
                $oShape->setOffsetX(CommonDrawing::emuToPixels((int) $oElement->getAttribute('x')));
            }
            if ($oElement->hasAttribute('y')) {
                $oShape->setOffsetY(CommonDrawing::emuToPixels((int) $oElement->getAttribute('y')));
            }
        }

        $oElement = $document->getElement('p:xfrm/a:ext', $node);
        if ($oElement instanceof DOMElement) {
            if ($oElement->hasAttribute('cx')) {
                $oShape->setWidth(CommonDrawing::emuToPixels((int) $oElement->getAttribute('cx')));
            }
            if ($oElement->hasAttribute('cy')) {
                $oShape->setHeight(CommonDrawing::emuToPixels((int) $oElement->getAttribute('cy')));
            }
        }

        $arrayElements = $document->getElements('a:graphic/a:graphicData/a:tbl/a:tblGrid/a:gridCol', $node);
        $oShape->setNumColumns($arrayElements->length);
        $oShape->createRow();
        foreach ($arrayElements as $key => $oElement) {
            if ($oElement instanceof DOMElement && $oElement->getAttribute('w')) {
                $oShape->getRow(0)->getCell($key)->setWidth(CommonDrawing::emuToPixels((int) $oElement->getAttribute('w')));
            }
        }

        $arrayElements = $document->getElements('a:graphic/a:graphicData/a:tbl/a:tr', $node);
        foreach ($arrayElements as $keyRow => $oElementRow) {
            if (!($oElementRow instanceof DOMElement)) {
                continue;
            }
            if ($oShape->hasRow($keyRow)) {
                $oRow = $oShape->getRow($keyRow);
            } else {
                $oRow = $oShape->createRow();
            }
            if ($oElementRow->hasAttribute('h')) {
                $oRow->setHeight(CommonDrawing::emuToPixels((int) $oElementRow->getAttribute('h')));
            }
            $arrayElementsCell = $document->getElements('a:tc', $oElementRow);
            foreach ($arrayElementsCell as $keyCell => $oElementCell) {
                if (!($oElementCell instanceof DOMElement)) {
                    continue;
                }
                $oCell = $oRow->getCell($keyCell);
                $oCell->setParagraphs([]);
                if ($oElementCell->hasAttribute('gridSpan')) {
                    $oCell->setColSpan((int) $oElementCell->getAttribute('gridSpan'));
                }
                if ($oElementCell->hasAttribute('rowSpan')) {
                    $oCell->setRowSpan((int) $oElementCell->getAttribute('rowSpan'));
                }

                foreach ($document->getElements('a:txBody/a:p', $oElementCell) as $oElementPara) {
                    if ($oElementPara instanceof DOMElement) {
                        $this->loadParagraph($document, $oElementPara, $oCell);
                    }
                }

                $oElementTcPr = $document->getElement('a:tcPr', $oElementCell);
                if ($oElementTcPr instanceof DOMElement) {
                    $numParagraphs = count($oCell->getParagraphs());
                    if ($numParagraphs > 0) {
                        if ($oElementTcPr->hasAttribute('vert')) {
                            $oCell->getParagraph(0)->getAlignment()->setTextDirection($oElementTcPr->getAttribute('vert'));
                        }
                        if ($oElementTcPr->hasAttribute('anchor')) {
                            $oCell->getParagraph(0)->getAlignment()->setVertical($oElementTcPr->getAttribute('anchor'));
                        }
                        if ($oElementTcPr->hasAttribute('marB')) {
                            $oCell->getParagraph(0)->getAlignment()->setMarginBottom((int) $oElementTcPr->getAttribute('marB'));
                        }
                        if ($oElementTcPr->hasAttribute('marL')) {
                            $oCell->getParagraph(0)->getAlignment()->setMarginLeft((int) $oElementTcPr->getAttribute('marL'));
                        }
                        if ($oElementTcPr->hasAttribute('marR')) {
                            $oCell->getParagraph(0)->getAlignment()->setMarginRight((int) $oElementTcPr->getAttribute('marR'));
                        }
                        if ($oElementTcPr->hasAttribute('marT')) {
                            $oCell->getParagraph(0)->getAlignment()->setMarginTop((int) $oElementTcPr->getAttribute('marT'));
                        }
                    }

                    $oFill = $this->loadStyleFill($document, $oElementTcPr);
                    if ($oFill instanceof Fill) {
                        $oCell->setFill($oFill);
                    }

                    $oBorders = new Borders();
                    $oElementBorderL = $document->getElement('a:lnL', $oElementTcPr);
                    if ($oElementBorderL instanceof DOMElement) {
                        $this->loadStyleBorder($document, $oElementBorderL, $oBorders->getLeft());
                    }
                    $oElementBorderR = $document->getElement('a:lnR', $oElementTcPr);
                    if ($oElementBorderR instanceof DOMElement) {
                        $this->loadStyleBorder($document, $oElementBorderR, $oBorders->getRight());
                    }
                    $oElementBorderT = $document->getElement('a:lnT', $oElementTcPr);
                    if ($oElementBorderT instanceof DOMElement) {
                        $this->loadStyleBorder($document, $oElementBorderT, $oBorders->getTop());
                    }
                    $oElementBorderB = $document->getElement('a:lnB', $oElementTcPr);
                    if ($oElementBorderB instanceof DOMElement) {
                        $this->loadStyleBorder($document, $oElementBorderB, $oBorders->getBottom());
                    }
                    $oElementBorderDiagDown = $document->getElement('a:lnTlToBr', $oElementTcPr);
                    if ($oElementBorderDiagDown instanceof DOMElement) {
                        $this->loadStyleBorder($document, $oElementBorderDiagDown, $oBorders->getDiagonalDown());
                    }
                    $oElementBorderDiagUp = $document->getElement('a:lnBlToTr', $oElementTcPr);
                    if ($oElementBorderDiagUp instanceof DOMElement) {
                        $this->loadStyleBorder($document, $oElementBorderDiagUp, $oBorders->getDiagonalUp());
                    }
                    $oCell->setBorders($oBorders);
                }
            }
        }
    }

    /**
     * @param Cell|RichText $oShape
     */
    protected function loadParagraph(XMLReader $document, DOMElement $oElement, $oShape): void
    {
        // Core
        $oParagraph = $oShape->createParagraph();
        $oParagraph->setRichTextElements([]);

        $oSubElement = $document->getElement('a:pPr', $oElement);
        if ($oSubElement instanceof DOMElement) {
            if ($oSubElement->hasAttribute('algn')) {
                $oParagraph->getAlignment()->setHorizontal($oSubElement->getAttribute('algn'));
            }
            if ($oSubElement->hasAttribute('fontAlgn')) {
                $oParagraph->getAlignment()->setVertical($oSubElement->getAttribute('fontAlgn'));
            }
            if ($oSubElement->hasAttribute('marL')) {
                $oParagraph->getAlignment()->setMarginLeft(CommonDrawing::emuToPixels((int) $oSubElement->getAttribute('marL')));
            }
            if ($oSubElement->hasAttribute('marR')) {
                $oParagraph->getAlignment()->setMarginRight(CommonDrawing::emuToPixels((int) $oSubElement->getAttribute('marR')));
            }
            if ($oSubElement->hasAttribute('indent')) {
                $oParagraph->getAlignment()->setIndent(CommonDrawing::emuToPixels((int) $oSubElement->getAttribute('indent')));
            }
            if ($oSubElement->hasAttribute('lvl')) {
                $oParagraph->getAlignment()->setLevel((int) $oSubElement->getAttribute('lvl'));
            }
            if ($oSubElement->hasAttribute('rtl')) {
                $oParagraph->getAlignment()->setIsRTL((bool) $oSubElement->getAttribute('rtl'));
            }

            $oElementLineSpacingPoints = $document->getElement('a:lnSpc/a:spcPts', $oSubElement);
            if ($oElementLineSpacingPoints instanceof DOMElement) {
                $oParagraph->setLineSpacingMode(Paragraph::LINE_SPACING_MODE_POINT);
                $oParagraph->setLineSpacing($oElementLineSpacingPoints->getAttribute('val') / 100);
            }
            $oElementLineSpacingPercent = $document->getElement('a:lnSpc/a:spcPct', $oSubElement);
            if ($oElementLineSpacingPercent instanceof DOMElement) {
                $oParagraph->setLineSpacingMode(Paragraph::LINE_SPACING_MODE_PERCENT);
                $oParagraph->setLineSpacing($oElementLineSpacingPercent->getAttribute('val') / 1000);
            }
            $oElementSpacingBefore = $document->getElement('a:spcBef/a:spcPts', $oSubElement);
            if ($oElementSpacingBefore instanceof DOMElement) {
                $oParagraph->setSpacingBefore($oElementSpacingBefore->getAttribute('val') / 100);
            }
            $oElementSpacingAfter = $document->getElement('a:spcAft/a:spcPts', $oSubElement);
            if ($oElementSpacingAfter instanceof DOMElement) {
                $oParagraph->setSpacingAfter($oElementSpacingAfter->getAttribute('val') / 100);
            }

            $oParagraph->getBulletStyle()->setBulletType(Bullet::TYPE_NONE);

            $oElementBuFont = $document->getElement('a:buFont', $oSubElement);
            if ($oElementBuFont instanceof DOMElement) {
                if ($oElementBuFont->hasAttribute('typeface')) {
                    $oParagraph->getBulletStyle()->setBulletFont($oElementBuFont->getAttribute('typeface'));
                }
            }
            $oElementBuChar = $document->getElement('a:buChar', $oSubElement);
            if ($oElementBuChar instanceof DOMElement) {
                $oParagraph->getBulletStyle()->setBulletType(Bullet::TYPE_BULLET);
                if ($oElementBuChar->hasAttribute('char')) {
                    $oParagraph->getBulletStyle()->setBulletChar($oElementBuChar->getAttribute('char'));
                }
            }
            $oElementBuAutoNum = $document->getElement('a:buAutoNum', $oSubElement);
            if ($oElementBuAutoNum instanceof DOMElement) {
                $oParagraph->getBulletStyle()->setBulletType(Bullet::TYPE_NUMERIC);
                if ($oElementBuAutoNum->hasAttribute('type')) {
                    $oParagraph->getBulletStyle()->setBulletNumericStyle($oElementBuAutoNum->getAttribute('type'));
                }
                if ($oElementBuAutoNum->hasAttribute('startAt') && 1 != $oElementBuAutoNum->getAttribute('startAt')) {
                    $oParagraph->getBulletStyle()->setBulletNumericStartAt($oElementBuAutoNum->getAttribute('startAt'));
                }
            }
            $oElementBuClr = $document->getElement('a:buClr', $oSubElement);
            if ($oElementBuClr instanceof DOMElement) {
                $oColor = new Color();
                /**
                 * @todo Create protected for reading Color
                 */
                $oElementColor = $document->getElement('a:srgbClr', $oElementBuClr);
                if ($oElementColor instanceof DOMElement) {
                    $oColor->setRGB($oElementColor->hasAttribute('val') ? $oElementColor->getAttribute('val') : null);
                }
                $oParagraph->getBulletStyle()->setBulletColor($oColor);
            }
        }
        $arraySubElements = $document->getElements('(a:r|a:br)', $oElement);
        foreach ($arraySubElements as $oSubElement) {
            if (!($oSubElement instanceof DOMElement)) {
                continue;
            }
            if ('a:br' == $oSubElement->tagName) {
                $oParagraph->createBreak();
            }
            if ('a:r' == $oSubElement->tagName) {
                $oElementrPr = $document->getElement('a:rPr', $oSubElement);
                if (is_object($oElementrPr)) {
                    $oText = $oParagraph->createTextRun();

                    if ($oElementrPr->hasAttribute('b')) {
                        $att = $oElementrPr->getAttribute('b');
                        $oText->getFont()->setBold('true' == $att || '1' == $att ? true : false);
                    }
                    if ($oElementrPr->hasAttribute('i')) {
                        $att = $oElementrPr->getAttribute('i');
                        $oText->getFont()->setItalic('true' == $att || '1' == $att ? true : false);
                    }
                    if ($oElementrPr->hasAttribute('strike')) {
                        $oText->getFont()->setStrikethrough('noStrike' == $oElementrPr->getAttribute('strike') ? false : true);
                    }
                    if ($oElementrPr->hasAttribute('sz')) {
                        $oText->getFont()->setSize((int) ($oElementrPr->getAttribute('sz') / 100));
                    }
                    if ($oElementrPr->hasAttribute('u')) {
                        $oText->getFont()->setUnderline($oElementrPr->getAttribute('u'));
                    }
                    // Color
                    $oElementSrgbClr = $document->getElement('a:solidFill/a:srgbClr', $oElementrPr);
                    if (is_object($oElementSrgbClr) && $oElementSrgbClr->hasAttribute('val')) {
                        $oColor = new Color();
                        $oColor->setRGB($oElementSrgbClr->getAttribute('val'));
                        $oText->getFont()->setColor($oColor);
                    }
                    // Hyperlink
                    $oElementHlinkClick = $document->getElement('a:hlinkClick', $oElementrPr);
                    if (is_object($oElementHlinkClick)) {
                        $oText->setHyperlink(
                            $this->loadHyperlink($document, $oElementHlinkClick, $oText->getHyperlink())
                        );
                    }
                    // Font
                    $oElementFontFormat = null;
                    $oElementFontFormatLatin = $document->getElement('a:latin', $oElementrPr);
                    if (is_object($oElementFontFormatLatin)) {
                        $oText->getFont()->setFormat(Font::FORMAT_LATIN);
                        $oElementFontFormat = $oElementFontFormatLatin;
                    }
                    $oElementFontFormatEastAsian = $document->getElement('a:ea', $oElementrPr);
                    if (is_object($oElementFontFormatEastAsian)) {
                        $oText->getFont()->setFormat(Font::FORMAT_EAST_ASIAN);
                        $oElementFontFormat = $oElementFontFormatEastAsian;
                    }
                    $oElementFontFormatComplexScript = $document->getElement('a:cs', $oElementrPr);
                    if (is_object($oElementFontFormatComplexScript)) {
                        $oText->getFont()->setFormat(Font::FORMAT_COMPLEX_SCRIPT);
                        $oElementFontFormat = $oElementFontFormatComplexScript;
                    }
                    if (is_object($oElementFontFormat) && $oElementFontFormat->hasAttribute('typeface')) {
                        $oText->getFont()->setName($oElementFontFormat->getAttribute('typeface'));
                    }

                    //} else {
                    // $oText = $oParagraph->createText();

                    $oSubSubElement = $document->getElement('a:t', $oSubElement);
                    $oText->setText($oSubSubElement->nodeValue);
                }
            }
        }
    }

    protected function loadHyperlink(XMLReader $xmlReader, DOMElement $element, Hyperlink $hyperlink): Hyperlink
    {
        if ($element->hasAttribute('tooltip')) {
            $hyperlink->setTooltip($element->getAttribute('tooltip'));
        }
        if ($element->hasAttribute('r:id') && isset($this->arrayRels[$this->fileRels][$element->getAttribute('r:id')]['Target'])) {
            $hyperlink->setUrl($this->arrayRels[$this->fileRels][$element->getAttribute('r:id')]['Target']);
        }
        if ($subElementExt = $xmlReader->getElement('a:extLst/a:ext', $element)) {
            if ($subElementExt->hasAttribute('uri') && $subElementExt->getAttribute('uri') == '{A12FA001-AC4F-418D-AE19-62706E023703}') {
                $hyperlink->setIsTextColorUsed(true);
            }
        }

        return $hyperlink;
    }

    protected function loadStyleBorder(XMLReader $xmlReader, DOMElement $oElement, Border $oBorder): void
    {
        if ($oElement->hasAttribute('w')) {
            $oBorder->setLineWidth($oElement->getAttribute('w') / 12700);
        }
        if ($oElement->hasAttribute('cmpd')) {
            $oBorder->setLineStyle($oElement->getAttribute('cmpd'));
        }

        $oElementNoFill = $xmlReader->getElement('a:noFill', $oElement);
        if ($oElementNoFill instanceof DOMElement && Border::LINE_SINGLE == $oBorder->getLineStyle()) {
            $oBorder->setLineStyle(Border::LINE_NONE);
        }

        $oElementColor = $xmlReader->getElement('a:solidFill/a:srgbClr', $oElement);
        if ($oElementColor instanceof DOMElement) {
            $oBorder->setColor($this->loadStyleColor($xmlReader, $oElementColor));
        }

        $oElementDashStyle = $xmlReader->getElement('a:prstDash', $oElement);
        if ($oElementDashStyle instanceof DOMElement && $oElementDashStyle->hasAttribute('val')) {
            $oBorder->setDashStyle($oElementDashStyle->getAttribute('val'));
        }
    }

    protected function loadStyleColor(XMLReader $xmlReader, DOMElement $oElement): Color
    {
        $oColor = new Color();
        $oColor->setRGB($oElement->getAttribute('val'));
        $oElementAlpha = $xmlReader->getElement('a:alpha', $oElement);
        if ($oElementAlpha instanceof DOMElement && $oElementAlpha->hasAttribute('val')) {
            $alpha = strtoupper(dechex((($oElementAlpha->getAttribute('val') / 1000) / 100) * 255));
            $oColor->setRGB($oElement->getAttribute('val'), $alpha);
        }

        return $oColor;
    }

    protected function loadStyleFill(XMLReader $xmlReader, DOMElement $oElement): ?Fill
    {
        // Gradient fill
        $oElementFill = $xmlReader->getElement('a:gradFill', $oElement);
        if ($oElementFill instanceof DOMElement) {
            $oFill = new Fill();
            $oFill->setFillType(Fill::FILL_GRADIENT_LINEAR);

            $oElementColor = $xmlReader->getElement('a:gsLst/a:gs[@pos="0"]/a:srgbClr', $oElementFill);
            if ($oElementColor instanceof DOMElement && $oElementColor->hasAttribute('val')) {
                $oFill->setStartColor($this->loadStyleColor($xmlReader, $oElementColor));
            }

            $oElementColor = $xmlReader->getElement('a:gsLst/a:gs[@pos="100000"]/a:srgbClr', $oElementFill);
            if ($oElementColor instanceof DOMElement && $oElementColor->hasAttribute('val')) {
                $oFill->setEndColor($this->loadStyleColor($xmlReader, $oElementColor));
            }

            $oRotation = $xmlReader->getElement('a:lin', $oElementFill);
            if ($oRotation instanceof DOMElement && $oRotation->hasAttribute('ang')) {
                $oFill->setRotation(CommonDrawing::angleToDegrees((int) $oRotation->getAttribute('ang')));
            }

            return $oFill;
        }

        // Solid fill
        $oElementFill = $xmlReader->getElement('a:solidFill', $oElement);
        if ($oElementFill instanceof DOMElement) {
            $oFill = new Fill();
            $oFill->setFillType(Fill::FILL_SOLID);

            $oElementColor = $xmlReader->getElement('a:srgbClr', $oElementFill);
            if ($oElementColor instanceof DOMElement) {
                $oFill->setStartColor($this->loadStyleColor($xmlReader, $oElementColor));
            }

            return $oFill;
        }

        return null;
    }

    protected function loadRels(string $fileRels): void
    {
        $sPart = $this->oZip->getFromName($fileRels);
        if (false !== $sPart) {
            $xmlReader = new XMLReader();
            /* @phpstan-ignore-next-line */
            if ($xmlReader->getDomFromString($sPart)) {
                foreach ($xmlReader->getElements('*') as $oNode) {
                    if (!($oNode instanceof DOMElement)) {
                        continue;
                    }
                    $this->arrayRels[$fileRels][$oNode->getAttribute('Id')] = [
                        'Target' => $oNode->getAttribute('Target'),
                        'Type' => $oNode->getAttribute('Type'),
                    ];
                }
            }
        }
    }

    /**
     * @param AbstractSlide|Note $oSlide
     * @param DOMNodeList<DOMNode> $oElements
     *
     * @throws FeatureNotImplementedException
     *
     * @internal param $baseFile
     */
    protected function loadSlideShapes($oSlide, DOMNodeList $oElements, XMLReader $xmlReader): void
    {
        foreach ($oElements as $oNode) {
            if (!($oNode instanceof DOMElement)) {
                continue;
            }
            switch ($oNode->tagName) {
                case 'p:graphicFrame':
                    $this->loadShapeTable($xmlReader, $oNode, $oSlide);
                    break;
                case 'p:pic':
                    $this->loadShapeDrawing($xmlReader, $oNode, $oSlide);
                    break;
                case 'p:sp':
                    $this->loadShapeRichText($xmlReader, $oNode, $oSlide);
                    break;
                default:
                    //throw new FeatureNotImplementedException();
            }
        }
    }
}
