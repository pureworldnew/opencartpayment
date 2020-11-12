<?php
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


// Admin language file

require_once(DIR_SYSTEM . 'adsmart_search.php');


if (strpos($_GET['route'], substr(basename(__FILE__), 0, -4)) === false && strpos($_GET['route'], 'layout') === false) {
	$_['heading_title']    = '<span style="position:relative; padding-left:26px;font-weight:bold;"><img style="width:20px; position:absolute; bottom:-3px; left:-1px;" src="view/image/adsmartsrc_logo.jpg" />Advanced Smart Search '.ADSMART_SRC_VERSION .'</span>';
}
else {
	$_['heading_title']    = 'Advanced Smart Search '.ADSMART_SRC_VERSION;
}

// Text

if (defined('ADSMART_SRC_DEMO_RESET_TIME'))  $demo_reset_time = ADSMART_SRC_DEMO_RESET_TIME;
else $demo_reset_time = '';

$_['text_demo_postit']			=  'Sul sito demo le impostazioni vengono resettate ogni ' . $demo_reset_time . ' minuti';

$_['text_test_it']				= 'Scrivi qui dei nomi prodotto per provare il Live Search';
$_['text_test_it_placeholder']	= 'Prova qui il Live Search';
$_['text_keep_open']			= 'Mantieni aperta la lista';

$_['text_search_analytics']	= 'Search Analytics';
$_['text_search_options']	= 'Opzioni generali di ricerca';
$_['text_style']    		= 'Opzioni e stile del Live Search';
$_['text_user_guide']		= 'Guida utente';
$_['text_license']			= 'Licenza';

$_['text_enable_module']	= 'Abilita Advanced Smart Search';
$_['text_search_algorithm']	= 'Algoritmo di ricerca';
$_['text_default']			= 'Predefinito';
$_['text_fast']				= 'Veloce';

$_['text_match_type']		= 'Tipo di corrispondenza';
$_['text_exact_match']		= 'Esatta';
$_['text_broad_match']		= 'Estesa';

$_['text_sort_order']		= 'Ordinamento predefinito';
$_['text_optn_relevance']	= 'Rilevanza';
$_['text_optn_date_desc']	= 'Data (ultimi arrivi su)';
$_['text_optn_date_asc']	= 'Data (meno recenti su)';


$_['text_translate_extra_sort']	= 'Testi personalizzati per i tipi di ordinamento extra';
$_['text_add_translation']		= 'Aggiungi traduzione!';

$_['text_relevance']			= 'Campi prodotto in cui cercare<br /><br /><span style="padding-left:80px;">+</span><br /><br /> Setup Rilevanza';
$_['text_enable_all']			= 'Abilita tutto';
$_['text_disable_all']			= 'Disabilita tutto';
$_['text_field_name']			= 'Campo prodotto';
$_['text_include_plurals']		= 'Includi plurali';

$_['text_include_partial_words']	= 'Includi parole parziali';
$_['text_partial_word_length']		= 'Lunghezza minima parola parziale';
$_['text_current_search_algorithm']	= 'Algoritmo di ricerca corrente';
$_['text_what_is_this']				= 'Cosa &egrave; questo?';


$_['text_include_misspellings']	= 'Rileva errori ortografici';
$_['text_misspelling_tolerance']= 'Tolleranza per gli errori';


$_['text_cache_manager']			= 'Gestore Cache';
$_['text_cache_update_frequency']	= 'Frequenza aggiornamento Cache';
$_['text_update_search_cache']		= 'Aggiorna cache';
$_['text_clear_search_cache']		= 'Cancella cache';
$_['text_search_cache_updated']		= 'Cache aggiornata!';
$_['text_search_cache_cleared']		= 'Cache cancellata!';

$_['text_dialog_update_cache_title']	= 'Aggiornare la cache prima di salvare?';
$_['text_dialog_update_cache_text']		= '<span>La cache potrebbe non essere aggiornata con le impostazioni di ricerca correnti. Scegli una delle seguenti opzioni:</span><ul><li><b>Salva e aggiorna la cache (consigliato)</b>. Se la cache &egrave; troppo grande, l&#39;aggiornamento potrebbe richiedere diversi secondi.</li><li><b>Salva e cancella la vecchia cache.</b></li><li><b>Salva soltanto</b>. I risultati di ricerca futuri non precedentemente salvati in cache saranno inseriti in cache con le nuove impostazioni. I vecchi risultati ancora in cache <u>saranno mostrati agli utenti</u> fino alla loro data di scadenza.</li></ul>';

$_['text_dialog_algorithm_fast_and_indexing_title']	= 'Indicizzazione tabelle richiesta';
$_['text_dialog_algorithm_fast_and_indexing_text']	= 'Per migliorare la velocit&agrave; di ricerca, questa funzionalit&agrave; aggiunger&agrave; indici FULL TEXTal tuo database. Vuoi continuare?';
	
$_['text_no_indexing_then_default_algorithm_title']	= 'Cambiare tipo di algoritmo?';
$_['text_no_indexing_then_default_algorithm_text']	= 'L&#39;algoritmo di ricerca corrente &egrave; impostato su FAST. Se disabiliti l&#39;indicizzazione delle tabelle, l&#39;algoritmo di ricerca sar&agrave; impostato su DEFAULT. Vuoi continuare?';

	
$_['text_db_optimization']		= 'Ottimizzazione database';
$_['text_index_db_tables']		= 'Indicizza le tabelle del database';
$_['text_rebuild_indexes']		= 'Ricostruire gli indici?';
$_['text_rebuild']				= 'Ricostruisci';
$_['text_indexes_rebuilt']		= 'Gli indici sono stati ricostruiti.';
$_['text_wait_slow_indexing']	= 'Attendere, l&#39;indicizzazione sta richiedendo pi&ugrave; tempo del previsto...';

$_['text_curr_srv_conf']		= 'Configurazione corrente del server MySQL';	
$_['text_mysql_setting_name']	= 'Nome';	
$_['text_mysql_var_value']		= 'Valore';	

$_['text_product_stats']	= 'Statistiche prodotti';	
$_['text_product_total']	= 'Numero di prodotti attivi';	
	

// Help

$_['text_help_fast']				= 'Fornisce <b>ricerche veloci</b> anche su grandi databases. Si consiglia l&#39;uso di questo algoritmo a meno che non si abbia bisogno di funzionalit&agrave; specifiche disponibili sono con l&#39;algoritmo predefinito.';

$_['text_help_default']				= 'La principale differenza con l&#39;algoritmo veloce &egrave; la capacit&agrave; di trovare <i>"parole all&#39;interno di parole"</i>, non solo <i>"parole che iniziano con"</i>, quando l&#39;opzione <b>Includi parole parziali</b> &egrave; abilitata.';
	
$_['text_help_exact_match']			= 'I risultati di ricerca contengono <b>tutte</b> le parole della query di ricerca. Le parole possono essere in qualsiasi ordine. <a href="#exact-match">Clicca qui per vedere qualche esempio</a>';

$_['text_help_broad_match']			= 'I risultati contengono <b>almeno una o pi&ugrave;</b> parole presenti nella stringa di ricerca, in qualunque ordine. La query di ricerca pu&ograve; contenere altre parole non incluse in nessun campo prodotto. <a href="#broad-match">Leggi qui</a>.';

$_['text_help_sort_order']			= 'Scegli l&#39;ordinamento predefinito per la lista dei risultati. Note: <br/>- Se scegli l&#39;ordinamento "<i>Rilevanza</i>", ricorda di configurarlo nella sezione <b>Setup Rilevanza</b>.<br />- L&#39;ordinamento "<i>Default</i>" pu&ograve; essere configurato da ogni pagina prodotto (dal lato amministrativo, vai su <i>Catalogo</i> -> <i>Prodotti</i> -> <i>Modifica</i> -> tab <i>Dati</i> -> <i>Ordinamento</i>.';	

$_['text_help_translate_sort_order']= 'Traduci/cambia i testi per i tipi di ordinamento disponibili con questa estensione. <b>I testi per i tipi di ordinamento predefiniti (nome, prezzo ecc.) sono letti dai files della lingua di Opencart</b>.';	


$_['text_help_relevance']			= 'Un campo prodotto &egrave; un contenitore di testo memorizzato nel database. Scegli <b>in quali campi prodotto eseguire la ricerca</b> cliccando sui ckecboxes sulla sinistra di ogni nome campo. Per ottimizzare la velocit&agrave; di ricerca, seleziona solo i campi contenenti <b><i>keywords</i></b> utili ad identificare un prodotto.<br /><br />Il passo successivo &egrave; il setup della <b>rilevanza dei campi</b>. Scegli quali campi sono pi&ugrave;/meno rilevanti spostandoli su e gi&ugrave; nella lista (muovi il mouse su un campo e quando vedi il puntatore con le quattro frecce, trascinalo e rilascialo nella posizione desiderata).  <a href="#relevance">Clicca qui</a> per saperne di pi&ugrave;.';


$_['text_help_partial_words']		= 'Questa opzione estende le corrispondenze alle parole parziali. Per esempio, la parola <b><i>bottiglie</i></b> potr&agrave; essere usata per trovare parole composte come <b>apri<i class="match">bottiglie</i></b>. Usa questa opzione con cautela. <a href="#partial-words">Leggi qui</a> per sapere quando conviene utilizzarla e come evitare <a href="#partial-words-and-speed">problemi di velocit&agrave; nelle ricerche</a>.';


$_['text_help_add_modules']			= 'Hai bisogno di un campo di ricerca su un layout e poasizione particolare? Allora usa questa funzione per aggiungere il tuo campo di ricerca personalizzato!';

$_['text_help_db_optimization']		= 'Seleziona questo checkbox pr velocizzare il processo di ricerca. <a href="#index-tables">Leggi qui</a> per sapere cosa sono gli indici FULL TEXT e come possono migliorare le performances di ricerca. Per creare gli indici, seleziona il checkbox e clicca su <b>Salva</b>.<br /> Il bottone "Ricostruisci" (Rebuild) (mostrato quando il checkbox &egrave; selezionato) pu&ograve; essere usato per ricostruire indici danneggiati o dopo l&#39;aggiornamento dei files di configurazione di MySQL (questi files possono essere modificati solo su server VPS/dedicati, leggi il paragrafo <a href="#mysql-config">Fine tuning MySQL configuration</a>). Su grandi databases, la ricostruzione degli indici pu&ograve; richiedere diverso tempo. <a href="#rebuild-indexes">Leggi qui</a>';

$_['text_help_update_single_char']	= 'Consigliato per: <br /> - Server veloci<br /> - databases medio-piccoli<br /> - una configurazione del motore di ricerca ben bilanciata. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#refresh-mode">Leggi qui</a>';

$_['text_help_update_entire_word']	= 'Consigliato per: <br /> - server lenti<br /> - databases grandi<br /> - una configurazione del motore troppo pesante. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#refresh-mode">Leggi qui</a>';

$_['text_preset_styles']			= 'Stili predefiniti';

$_['text_enable_live_search']		= 'Abilita il Live Search';

$_['text_add_modules']				= 'Aggiungi campi di ricerca personalizzati';
$_['text_content_top']    			= 'Contenuto in alto';
$_['text_content_bottom'] 			= 'Contenuto in basso';
$_['text_column_left']    			= 'Colonna sinistra';
$_['text_column_right']   			= 'Colonna destra';

$_['text_live_search_options']		= 'Modo di aggiornamento';
$_['text_live_search_style']		= 'Stile del Live Search';

$_['text_dropdown_display_img']		= 'Mostra immagini';
$_['text_dropdown_img_size']		= 'Dimensione immagine';
$_['text_dropdown_img_border_color']= 'Colore bordo immagine';

$_['text_dropdown_display_price']	= 'Mostra prezzo';
$_['text_dropdown_display_rating']	= 'Mostra rating';

$_['text_dropdown_width']			= 'Larghezza finestra';
$_['text_dropdown_text_size']		= 'Dimensione testo';
$_['text_dropdown_text_color']		= 'Colore testo';

$_['text_dropdown_bg_color']			= 'Colore sfondo';
$_['text_dropdown_border_color']		= 'Colore bordo';
$_['text_dropdown_hover_bg_color']		= 'Colore passaggio mouse';

$_['text_dropdown_max_num_results']		= 'Max numero di risultati mostrati';

$_['text_dropdown_update_on']	= 'Aggiorna risultati dopo';
$_['text_entire_word']			= 'ogni parola';
$_['text_single_char']			= 'ogni carattere';

				
				
$_['text_dropdown_no_results']				= 'Testo "Nessun risultato"';
$_['text_dropdown_no_results_sample_text']	= 'Nessun risultato';

$_['text_dropdown_show_all']				= 'Testo "Mostra tutto"';
$_['text_dropdown_show_all_sample_text']	= 'Mostra tutto';

$_['text_module']			= 'Moduli';
$_['text_entry']			= 'Entry';
$_['text_show']				= 'Mostra';
$_['text_enable']			= 'Abilita';
$_['text_enabled']			= 'Abilitato';
$_['text_disable']			= 'Disabilita';
$_['text_disabled']			= 'Disabilitato';
$_['text_yes']				= 'S&igrave;';
$_['text_no']				= 'No';


$_['text_help']				= 'Aiuto';
$_['text_select']			= 'Seleziona';
$_['text_select_all']		= 'Tutto';
$_['text_deselect_all']		= 'Nessuno';
$_['text_required']			= 'Richiesto';
$_['text_reset_default']	= 'Ripristina valori <br /> predefiniti';


$_['text_warning_select_style']			= '<b>Nota</b>: Se scegli uno stile predefinito, lo stile personalizzato (lo trovi sotto la voce Custom Style) non ancora salvato sar&agrave; sovrascritto. Se non vuoi perdere lo stile appena creato, salva la pagina prima di continuare.';
$_['text_info_save_in_custom_style']	= 'Le tue impostazioni verranno salvate nello stile personalizzato (Per fare ci&ograve; clicca su <b>Salva</b>)';

// buttons
$_['button_add_module']    	= 'Aggiungi modulo';
$_['button_remove']    		= 'Rimuovi modulo';
$_['button_save']			= 'Salva';
$_['button_save_continue']	= 'Salva e Continua';

// Entry

$_['entry_meta_keyword'] 	 = 'SEO Meta Tag Keywords';
$_['entry_meta_description'] = 'SEO Meta Tag Description';

$_['entry_name']             = 'Nome prodotto';
$_['entry_description']      = 'Descrizione prodotto';
$_['entry_tag']          	 = 'Tags prodotto';

$_['entry_model']            = 'Modello';
$_['entry_sku']              = 'SKU';
$_['entry_upc']              = 'UPC';
$_['entry_ean']              = 'EAN';
$_['entry_jan']              = 'JAN';
$_['entry_isbn']             = 'ISBN';
$_['entry_mpn']              = 'MPN';

$_['entry_location']			= 'Ubicazione';
$_['entry_manufacturer_name']	= 'Nome produttore';

$_['entry_attribute_group_name']	= 'Nome Gruppo Attributo';
$_['entry_attribute_name']     		= 'Nome Attributo';
$_['entry_attribute_description']	= 'Descrizione Attributo';

$_['entry_option_name']				= 'Nome Opzione';
$_['entry_option_value']			= 'Valore Opzione';

$_['entry_category_name']			= 'Nome Categoria';

$_['entry_dimension']     			= 'Dimensioni (Largh. x Alt.)<br />(lascia vuoto per dimensioni autom.):';
$_['entry_layout']        			= 'Layout:';
$_['entry_position']      			= 'Posizione:';
$_['entry_status']        			= 'Stato:';
$_['entry_sort_order']    			= 'Ordinamento:';


										$user_guide = file_get_contents(DIR_APPLICATION.'language/english/module/adSmartSrc_help.php');
$_['user_guide'] 					=	$user_guide;

// install / uninstall / Save

$_['success_install']		= 'Per completare l&#39;installazione clicca <b>Modifica</b>, poi su <b>Salva</b>.';
$_['success_uninstall']		= '<b>Advanced Smart Search</b> disinstallato con successo.';
$_['error_install']			= 'Attenzione: impossibile installare <b>Advanced Smart Search</b>. XML mancante?';
$_['error_uninstall']		= 'Attenzione: Si &egrave; verificato un errore durante la disinstallazione di <b>Advanced Smart Search</b>. XML mancante?';
$_['text_save_success']		= '<b>Advanced Smart Search</b> &egrave; stato salvato con successo';
$_['text_error']			= 'Errore';
$_['text_saving']			= 'Salvataggio';
$_['text_cancel_save']		= 'Annulla salvataggio';
$_['text_wait_slow_save']	= '<b>Attendere</b>, l&#39;operazione di salvataggio sta richiedendo pi&ugrave; tempo del previsto. Ci&ograve; pu&ograve; verificarsi se PHP &egrave; installato su <b>sistemi Windows</b> e se il <b>database is big</b>.<br /> Se interrompi il processo di salvataggio <b>alcuni indici potrebbero non aggiornarsi correttamente</b>.';
$_['save_aborted']			= 'Salvataggio interrotto.';
$_['text_wait']				= 'Attendere...';

