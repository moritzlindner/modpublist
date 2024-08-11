# Joomla Publication List Module

This Joomla module allows users to retrieve article information by PubMed ID and display them in a table with an Altmetrics badge. The module is designed to be easily configurable with various options to customize the display of the article information.
## Features

    Retrieve and display article information using PubMed IDs.
    Show Altmetrics badges alongside article information.
    Configure font size and format options.
    Input multiple PubMed IDs, each on a new line.

## Installation

    Download the latest version of the module.
    In your Joomla admin panel, navigate to Extensions > Manage > Install.
    Upload the module package file and click Install.
    After installation, navigate to Extensions > Modules, find Publication List and enable it.

## Configuration

    PMIDs: Input the PubMed IDs, one per line.
    Font Size: Set the font size for the displayed text.
    Short Format: Option to display the article information in a short format.

## File Structure

    mod_publist.xml: The manifest file that defines the module's configuration.
    mod_publist.php: The main PHP file that handles data retrieval and display.
    helper.php: Helper functions used by the module.
    tmpl/default.php: The default template for displaying the article list.
    index.html and tmpl/index.html: Empty files to prevent directory listings.

## Author

    Moritz Lindner

License

This Joomla module is licensed under the GNU General Public License v2.0.# modpublist
A Joomla module generating a publication list including altmetric badges from a list of pubmed IDs.
