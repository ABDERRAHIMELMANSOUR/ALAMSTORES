# -*- coding: utf-8 -*-
"""
French page copy for the Alam Stores static site.

IMPORTANT — this text was written for the static rebuild. It is NOT the
original wording from the WordPress database, which was not part of the export.
It describes the product categories generically and deliberately avoids any
company-specific claim that could not be verified (founding year, number of
projects, certifications, guarantees, prices). Review and adjust before
publishing, and replace the CONTACT values in tools/build_pages.py.

Schema per slug:
    meta     str   meta description, aim for 140-160 characters
    lead     str   intro paragraph rendered under the page title
    sections list  {"h": heading, "p": [paragraphs], "ul": [bullets]}
"""

CONTENT = {

    # ------------------------------------------------------------------ home
    "home": {
        "meta": "Alam Stores, spécialiste de l'aménagement et de la décoration au "
                "Maroc : stores intérieurs et extérieurs, pergolas, moustiquaires et "
                "motorisations sur mesure.",
        "lead": "Alam Stores conçoit, fabrique et installe des solutions de protection "
                "solaire et d'aménagement sur mesure : stores intérieurs, stores "
                "extérieurs, pergolas, parasols, moustiquaires et motorisations. "
                "Chaque projet est étudié en fonction de vos ouvertures, de "
                "l'exposition et de l'usage de la pièce.",
        "sections": [
            {
                "h": "Une gamme complète pour l'intérieur et l'extérieur",
                "p": ["Du store enrouleur occultant qui plonge une chambre dans le noir "
                      "à la pergola bioclimatique qui rend une terrasse utilisable toute "
                      "l'année, nos gammes couvrent l'ensemble des besoins en gestion de "
                      "la lumière et du confort thermique, pour les particuliers comme "
                      "pour les professionnels."],
                "ul": [
                    "Stores intérieurs : enrouleurs, vénitiens, californiens, bateaux, "
                    "duo jour/nuit et panneaux japonais",
                    "Stores extérieurs : pergolas, parasols, toiles tendues et abris de voiture",
                    "Moustiquaires sur mesure pour fenêtres, portes et baies coulissantes",
                    "Motorisations et automatismes pour piloter vos stores au quotidien",
                ],
            },
            {
                "h": "Du conseil à la pose",
                "p": ["Nous intervenons sur l'ensemble du projet : visite et prise de "
                      "mesures, choix des toiles et des coloris, fabrication aux "
                      "dimensions exactes, puis installation. Cette continuité évite les "
                      "mauvaises surprises au montage et garantit un ajustement précis, "
                      "y compris sur des ouvertures atypiques."],
            },
            {
                "h": "Particuliers, hôtels, bureaux et commerces",
                "p": ["Un salon, une chambre d'hôtel, un open space ou une devanture "
                      "n'appellent pas les mêmes réponses. Nous adaptons le type de "
                      "store, le facteur d'ouverture de la toile et le mode de commande "
                      "à l'usage réel de chaque espace, avec des solutions homogènes "
                      "lorsqu'il s'agit d'équiper un bâtiment entier."],
            },
        ],
    },

    # --------------------------------------------------------------- societe
    "societe": {
        "meta": "Alam Stores, spécialiste marocain de l'aménagement et de la décoration : "
                "conception, fabrication et pose de stores, pergolas et moustiquaires sur mesure.",
        "lead": "Alam Stores est spécialisée dans l'aménagement et la décoration : "
                "protection solaire, gestion de la lumière et confort des espaces "
                "intérieurs et extérieurs, pour les particuliers comme pour les "
                "professionnels.",
        "sections": [
            {
                "h": "Notre métier",
                "p": ["Notre travail consiste à traduire une contrainte — trop de "
                      "soleil, un vis-à-vis gênant, une terrasse inutilisable l'été, "
                      "une chaleur excessive derrière une baie vitrée — en une solution "
                      "technique adaptée. Cela suppose de connaître aussi bien les "
                      "toiles et les mécanismes que la manière dont un espace est "
                      "réellement vécu."],
            },
            {
                "h": "Le sur-mesure comme principe",
                "p": ["Peu d'ouvertures sont standard. Nous fabriquons aux dimensions "
                      "relevées sur place, ce qui permet de traiter les baies "
                      "coulissantes, les fenêtres de forme particulière, les vérandas "
                      "et les grandes hauteurs sans compromis sur la finition ni sur le "
                      "fonctionnement."],
            },
            {
                "h": "Notre méthode",
                "p": ["Une démarche simple, en quatre temps, pensée pour que vous "
                      "sachiez à chaque étape où en est votre projet."],
                "ul": [
                    "Écoute du besoin : usage de la pièce, exposition, contraintes esthétiques",
                    "Visite technique et prise de mesures précises sur site",
                    "Proposition chiffrée avec choix des toiles, coloris et commandes",
                    "Fabrication puis pose par nos équipes, avec réglage et essai",
                ],
            },
            {
                "h": "Nos engagements",
                "p": ["Des conseils fondés sur la technique plutôt que sur la vente, "
                      "des matériaux choisis pour durer sous le climat marocain, et un "
                      "interlocuteur qui reste le même du premier contact jusqu'à la "
                      "réception du chantier."],
            },
        ],
    },

    # ----------------------------------------------------- stores intérieurs
    "stores-interieurs": {
        "meta": "Stores intérieurs sur mesure au Maroc : enrouleurs, vénitiens, "
                "californiens, bateaux, duo jour/nuit et panneaux japonais. Conseil, "
                "fabrication et pose.",
        "lead": "Le store intérieur règle trois choses à la fois : la quantité de "
                "lumière, l'intimité et la chaleur qui entre par le vitrage. Le bon "
                "choix dépend de l'orientation de la pièce, de son usage et de "
                "l'esthétique recherchée.",
        "sections": [
            {
                "h": "Choisir selon la pièce",
                "p": ["Une chambre demande de l'occultation, un bureau demande de "
                      "supprimer les reflets sans perdre la lumière, un salon exposé "
                      "plein sud demande d'abord de limiter la chaleur. Chaque famille "
                      "de stores répond à un de ces besoins."],
                "ul": [
                    "Chambre : store enrouleur occultant ou store duo jour/nuit",
                    "Bureau : store screen ou store vénitien aluminium, réglables au degré près",
                    "Salon : store bateau ou panneau japonais, pour une finition plus décorative",
                    "Cuisine et salle de bains : vénitien aluminium, facile à nettoyer",
                ],
            },
            {
                "h": "Comprendre les toiles",
                "p": ["Le comportement d'un store tient surtout à sa toile. Le facteur "
                      "d'ouverture indique la proportion de lumière laissée passer : "
                      "une toile occultante bloque la lumière, une toile tamisante la "
                      "diffuse, une toile screen filtre le rayonnement tout en "
                      "conservant la vue vers l'extérieur."],
            },
            {
                "h": "Mesures et pose",
                "p": ["Nous relevons les dimensions sur place et déterminons le type de "
                      "fixation — en applique, sous plafond ou dans l'embrasure — selon "
                      "la configuration. Ce relevé conditionne la qualité du résultat "
                      "final : un store bien dimensionné ne laisse pas passer la lumière "
                      "sur les côtés et coulisse sans effort."],
            },
        ],
    },

    "stores-enrouleurs": {
        "meta": "Stores enrouleurs sur mesure : occultant, tamisant, screen ou imprimé. "
                "Mécanisme discret, entretien simple, fabrication aux dimensions exactes.",
        "lead": "Le store enrouleur est la solution la plus simple et la plus discrète "
                "pour habiller une fenêtre. La toile s'enroule sur un tube, se règle à "
                "la hauteur voulue et disparaît presque entièrement une fois remontée.",
        "sections": [
            {
                "h": "Quatre toiles, quatre usages",
                "p": ["Le mécanisme reste identique : c'est la toile qui détermine le "
                      "résultat. Le choix se fait selon la lumière recherchée et le "
                      "niveau d'intimité souhaité."],
                "ul": [
                    "Occultant : bloque la lumière, pour les chambres et les salles de projection",
                    "Tamisant : diffuse une lumière douce et préserve l'intimité en journée",
                    "Screen : filtre le rayonnement solaire tout en gardant la vue vers l'extérieur",
                    "Imprimé : personnalisé avec le motif ou le visuel de votre choix",
                ],
            },
            {
                "h": "Commande manuelle ou motorisée",
                "p": ["La chaînette convient à la plupart des fenêtres accessibles. "
                      "Au-delà d'une certaine largeur, en hauteur ou derrière un "
                      "meuble, la motorisation devient le choix confortable : "
                      "commande à distance, pilotage groupé et programmation horaire."],
            },
            {
                "h": "Entretien",
                "p": ["Un dépoussiérage régulier suffit dans la majorité des cas. Les "
                      "toiles screen et les toiles enduites se nettoient avec une éponge "
                      "humide, ce qui les rend adaptées aux pièces exposées à la "
                      "poussière ou aux projections."],
            },
        ],
    },

    "store-enrouleur-occultant": {
        "meta": "Store enrouleur occultant sur mesure : obscurité totale pour chambres "
                "et salles de réunion, avec coulisses latérales en option.",
        "lead": "Le store enrouleur occultant utilise une toile opaque, généralement "
                "doublée, qui arrête la lumière du jour. C'est la réponse la plus "
                "directe pour une chambre, une chambre d'enfant ou une salle nécessitant "
                "l'obscurité.",
        "sections": [
            {
                "h": "Obtenir une vraie obscurité",
                "p": ["Une toile occultante ne suffit pas à elle seule : la lumière "
                      "contourne le store par les côtés. Pour une obscurité réellement "
                      "complète, nous posons des coulisses latérales ou un coffre qui "
                      "referment le pourtour de la fenêtre. Sans ce dispositif, le "
                      "résultat reste une forte pénombre plutôt qu'un noir total."],
            },
            {
                "h": "Un effet sur la température",
                "p": ["La toile occultante limite aussi l'apport de chaleur par le "
                      "vitrage. Sur une fenêtre exposée à l'est ou à l'ouest, la "
                      "différence est nette aux heures les plus chaudes, ce qui allège "
                      "d'autant le travail de la climatisation."],
            },
            {
                "h": "Où l'installer",
                "ul": [
                    "Chambres et chambres d'enfant, pour le sommeil en journée comme la nuit",
                    "Chambres d'hôtel, où l'occultation fait partie du niveau de prestation attendu",
                    "Salles de réunion et de projection, pour la lisibilité des écrans",
                    "Pièces exposées plein sud ou plein ouest",
                ],
            },
        ],
    },

    "store-enrouleur-tamisant": {
        "meta": "Store enrouleur tamisant sur mesure : lumière douce et intimité "
                "préservée en journée, sans assombrir la pièce.",
        "lead": "Le store tamisant filtre la lumière au lieu de la bloquer. La pièce "
                "reste claire, mais la lumière directe est adoucie et les regards "
                "extérieurs sont atténués en journée.",
        "sections": [
            {
                "h": "Un compromis entre clarté et intimité",
                "p": ["C'est le choix courant pour un salon, une salle à manger ou un "
                      "bureau : on conserve la luminosité naturelle sans subir "
                      "l'éblouissement ni le vis-à-vis. La toile diffuse la lumière de "
                      "façon homogène, ce qui supprime les zones de contraste marqué "
                      "près des fenêtres."],
            },
            {
                "h": "Ce qu'il faut savoir sur l'intimité nocturne",
                "p": ["Une toile tamisante protège des regards tant qu'il fait plus "
                      "clair dehors que dedans. Le soir, lumière allumée, le rapport "
                      "s'inverse et des silhouettes deviennent perceptibles. Pour une "
                      "pièce en rez-de-chaussée ou avec un vis-à-vis proche, mieux vaut "
                      "prévoir une toile occultante ou une double installation."],
            },
            {
                "h": "Coloris et matières",
                "p": ["Les toiles claires renvoient la lumière et agrandissent "
                      "visuellement la pièce ; les teintes plus soutenues réduisent "
                      "l'éblouissement et donnent un rendu plus feutré. Nous présentons "
                      "les échantillons sur place, à la lumière réelle de la pièce."],
            },
        ],
    },

    "store-enrouleur-screen": {
        "meta": "Store enrouleur screen sur mesure : filtre le rayonnement solaire, "
                "supprime l'éblouissement et conserve la vue vers l'extérieur.",
        "lead": "La toile screen est un tissu technique micro-perforé. Elle arrête une "
                "grande partie du rayonnement solaire et de l'éblouissement tout en "
                "laissant voir à travers, ce qui en fait la référence pour les bureaux "
                "et les grandes baies vitrées.",
        "sections": [
            {
                "h": "Le facteur d'ouverture",
                "p": ["Le facteur d'ouverture exprime la proportion de vide dans le "
                      "tissage : plus il est bas, plus le store protège et moins on voit "
                      "à travers. C'est le principal réglage du confort, et il se "
                      "choisit en fonction de l'orientation de la façade."],
                "ul": [
                    "1 % à 3 % : protection maximale, vue plus discrète, adapté aux façades très exposées",
                    "5 % : le compromis le plus courant en bureau",
                    "10 % : vue dégagée, protection plus légère, pour les façades peu ensoleillées",
                ],
            },
            {
                "h": "Écrans et postes de travail",
                "p": ["Dans un espace de travail, l'enjeu n'est pas d'assombrir mais de "
                      "supprimer les reflets sur les écrans sans couper la lumière "
                      "naturelle. Le screen traite exactement ce point, ce qui explique "
                      "sa présence dans la plupart des immeubles tertiaires."],
            },
            {
                "h": "Confort thermique",
                "p": ["En réfléchissant une partie du rayonnement avant qu'il ne "
                      "traverse le vitrage, le screen réduit la surchauffe derrière les "
                      "grandes surfaces vitrées. L'effet est encore plus marqué "
                      "lorsqu'il est posé en extérieur."],
            },
        ],
    },

    "store-enrouleur-imprime": {
        "meta": "Store enrouleur imprimé sur mesure : impression haute définition de "
                "votre visuel, photo ou identité de marque sur toile occultante ou tamisante.",
        "lead": "Le store enrouleur imprimé reprend le mécanisme classique en "
                "personnalisant la toile : photo, motif, aplat de couleur ou identité "
                "visuelle, imprimés en haute définition aux dimensions exactes de la "
                "fenêtre.",
        "sections": [
            {
                "h": "Des usages décoratifs et professionnels",
                "p": ["Chez le particulier, il transforme une fenêtre sans intérêt en "
                      "élément de décoration. Chez le professionnel, il devient un "
                      "support de communication permanent : logo en vitrine, visuel "
                      "d'ambiance en salle, habillage cohérent d'un réseau de points de "
                      "vente."],
            },
            {
                "h": "Préparer votre visuel",
                "p": ["La qualité du rendu dépend d'abord du fichier fourni. Nous vous "
                      "indiquons la résolution nécessaire selon le format du store et "
                      "vérifions le fichier avant lancement. Un visuel trop petit, "
                      "agrandi à la taille d'une baie, se voit immédiatement."],
                "ul": [
                    "Fichier haute résolution, aux proportions de la fenêtre",
                    "Choix entre toile occultante et toile tamisante selon le rendu voulu",
                    "Marges de sécurité prévues autour des éléments importants du visuel",
                ],
            },
            {
                "h": "Rendu jour et nuit",
                "p": ["Sur une toile tamisante, le visuel est traversé par la lumière du "
                      "jour et prend un aspect lumineux. Sur une toile occultante, les "
                      "couleurs sont plus denses et plus contrastées. Le choix dépend de "
                      "l'effet recherché et du moment où le store est le plus regardé."],
            },
        ],
    },

    "stores-venitiens": {
        "meta": "Stores vénitiens sur mesure en bois ou en aluminium : orientation des "
                "lames au degré près pour doser la lumière et l'intimité.",
        "lead": "Le store vénitien est composé de lames horizontales orientables. En les "
                "inclinant, on dirige la lumière vers le plafond, on la coupe "
                "totalement ou on rétablit la vue, sans jamais avoir à remonter le store.",
        "sections": [
            {
                "h": "Un réglage continu, pas binaire",
                "p": ["C'est la différence essentielle avec un store enrouleur, qui ne "
                      "propose qu'un curseur haut-bas. Le vénitien permet de garder la "
                      "pièce lumineuse tout en supprimant le soleil direct et le "
                      "vis-à-vis, en jouant uniquement sur l'inclinaison des lames."],
            },
            {
                "h": "Bois ou aluminium",
                "p": ["Les deux matériaux couvrent des besoins différents, autant "
                      "esthétiques que pratiques."],
                "ul": [
                    "Bois : rendu chaleureux et naturel, pour les pièces de vie et les bureaux",
                    "Aluminium : lames fines, insensibles à l'humidité, faciles à nettoyer",
                ],
            },
            {
                "h": "Largeur des lames",
                "p": ["Des lames étroites donnent un aspect discret et technique ; des "
                      "lames larges marquent davantage la fenêtre et laissent passer plus "
                      "de lumière une fois ouvertes. Le choix dépend de la taille de "
                      "l'ouverture et du style de la pièce."],
            },
        ],
    },

    "store-venitien-bois": {
        "meta": "Store vénitien en bois sur mesure : lames en bois véritable, rendu "
                "chaleureux et réglage précis de la lumière pour salons et bureaux.",
        "lead": "Le store vénitien en bois associe le réglage précis des lames "
                "orientables à la chaleur d'un matériau naturel. Il structure la fenêtre "
                "et s'accorde particulièrement bien aux intérieurs en bois ou aux tons "
                "neutres.",
        "sections": [
            {
                "h": "Un matériau qui a du caractère",
                "p": ["Chaque lame conserve son veinage : la teinte n'est jamais "
                      "parfaitement uniforme d'une lame à l'autre, et c'est précisément "
                      "ce qui distingue le bois véritable d'une imitation. Les finitions "
                      "vont du bois clair naturel aux teintes foncées, en passant par "
                      "les laqués."],
            },
            {
                "h": "Où l'installer, et où l'éviter",
                "p": ["Le bois convient parfaitement aux salons, chambres, bibliothèques "
                      "et bureaux. En revanche, il supporte mal l'humidité permanente : "
                      "pour une salle de bains ou une cuisine très exposée à la vapeur, "
                      "l'aluminium reste plus adapté et vieillira mieux."],
            },
            {
                "h": "Entretien",
                "p": ["Un dépoussiérage régulier au chiffon sec ou au plumeau suffit. "
                      "Évitez de détremper les lames : un chiffon légèrement humide, "
                      "puis un essuyage immédiat, préserve la finition dans le temps."],
            },
        ],
    },

    "store-venitien-aluminium": {
        "meta": "Store vénitien en aluminium sur mesure : lames fines et résistantes, "
                "idéal pour bureaux, cuisines et salles de bains.",
        "lead": "Le store vénitien en aluminium offre le même réglage fin de la lumière "
                "que le bois, avec des lames plus fines, insensibles à l'humidité et "
                "très simples à entretenir.",
        "sections": [
            {
                "h": "Résistant et facile à vivre",
                "p": ["L'aluminium ne gonfle pas, ne se déforme pas et ne craint ni la "
                      "vapeur ni les projections. C'est ce qui en fait le choix par "
                      "défaut pour les cuisines, les salles de bains et toutes les "
                      "pièces techniques, là où le bois se dégraderait."],
            },
            {
                "h": "Discret sur la fenêtre",
                "p": ["Les lames fines occupent peu de place une fois relevées et se "
                      "font oublier sur les petites ouvertures. Sur les fenêtres "
                      "étroites ou les portes vitrées, cette discrétion est un vrai "
                      "avantage visuel."],
            },
            {
                "h": "Finitions",
                "ul": [
                    "Coloris unis, du blanc aux teintes sombres",
                    "Finitions mates, satinées ou métallisées",
                    "Lames perforées pour laisser filtrer davantage de lumière",
                ],
            },
        ],
    },

    "stores-californiens": {
        "meta": "Stores californiens sur mesure : bandes verticales orientables pour "
                "baies vitrées, grandes surfaces et espaces professionnels.",
        "lead": "Le store californien fonctionne avec des bandes verticales orientables "
                "qui pivotent sur elles-mêmes et se rassemblent sur le côté. C'est la "
                "solution de référence pour les grandes surfaces vitrées et les baies "
                "coulissantes.",
        "sections": [
            {
                "h": "Pensé pour les grandes largeurs",
                "p": ["Là où un store enrouleur devient lourd et difficile à manœuvrer, "
                      "le californien reste léger : le poids est réparti sur l'ensemble "
                      "des bandes et le rail supporte des largeurs importantes sans "
                      "fléchir. Il équipe naturellement les baies vitrées et les façades "
                      "de bureaux."],
            },
            {
                "h": "Suivre la course du soleil",
                "p": ["Les bandes verticales sont particulièrement efficaces contre le "
                      "soleil rasant du matin et de fin de journée, celui qui éblouit le "
                      "plus. Il suffit de les pivoter pour bloquer le rayonnement direct "
                      "tout en conservant la lumière ambiante."],
            },
            {
                "h": "Ouverture et passage",
                "p": ["Les bandes se regroupent d'un côté, des deux côtés ou au centre "
                      "selon la configuration. Devant une baie coulissante, cela permet "
                      "de dégager complètement le passage sans démonter quoi que ce soit."],
            },
        ],
    },

    "stores-bateaux": {
        "meta": "Stores bateaux sur mesure : plis horizontaux réguliers, aspect textile "
                "décoratif pour salons, chambres et salles à manger.",
        "lead": "Le store bateau se relève en formant des plis horizontaux réguliers. "
                "Il apporte l'aspect chaleureux d'un textile tout en occupant beaucoup "
                "moins de place qu'un rideau classique.",
        "sections": [
            {
                "h": "Un choix décoratif avant tout",
                "p": ["Là où l'enrouleur privilégie la discrétion technique, le store "
                      "bateau assume une présence textile : matières, tombé, plis "
                      "marqués. Il habille une fenêtre comme le ferait un rideau, mais "
                      "sans encombrer les côtés de l'ouverture."],
            },
            {
                "h": "Tissus et doublures",
                "p": ["Le choix du tissu détermine à la fois le rendu et la fonction. "
                      "Une doublure occultante peut être ajoutée derrière un tissu "
                      "décoratif pour obtenir l'aspect voulu côté pièce et "
                      "l'obscurcissement recherché côté fenêtre."],
                "ul": [
                    "Tissus unis pour un rendu sobre et graphique",
                    "Lin et matières naturelles pour une atmosphère plus douce",
                    "Doublure occultante ou thermique en option",
                ],
            },
            {
                "h": "Où il fonctionne le mieux",
                "p": ["Salons, chambres, salles à manger et fenêtres de taille moyenne. "
                      "Sur une très grande largeur, mieux vaut diviser en plusieurs "
                      "stores : les plis restent réguliers et la manœuvre plus légère."],
            },
        ],
    },

    "store-duo-jour-nuit": {
        "meta": "Store duo jour/nuit sur mesure : bandes alternées transparentes et "
                "opaques, du plein jour à l'occultation avec un seul store.",
        "lead": "Le store duo jour/nuit associe des bandes horizontales alternativement "
                "transparentes et opaques. En les faisant glisser les unes devant les "
                "autres, on passe progressivement de la pleine lumière à l'occultation.",
        "sections": [
            {
                "h": "Deux stores en un",
                "p": ["Lorsque les bandes opaques se superposent, le store filtre "
                      "fortement la lumière et protège des regards. Lorsqu'elles se "
                      "décalent face aux bandes transparentes, la vue et la clarté "
                      "reviennent. Ce réglage continu évite d'avoir à installer deux "
                      "équipements distincts sur la même fenêtre."],
            },
            {
                "h": "Un usage qui suit la journée",
                "p": ["C'est un choix pertinent pour les pièces dont l'usage change au "
                      "fil des heures : un séjour lumineux la journée qui doit devenir "
                      "intime le soir, ou une chambre d'ami servant aussi de bureau."],
            },
            {
                "h": "À savoir",
                "p": ["Le duo jour/nuit atténue fortement la lumière mais n'égale pas "
                      "une toile occultante posée avec coulisses. Pour une chambre "
                      "nécessitant l'obscurité complète, l'enrouleur occultant reste la "
                      "réponse la plus efficace."],
            },
        ],
    },

    "panneaux-japonais": {
        "meta": "Panneaux japonais sur mesure : grands panneaux coulissants pour baies "
                "vitrées, cloisons décoratives et séparation d'espaces.",
        "lead": "Le panneau japonais se compose de grands panneaux de tissu plats qui "
                "coulissent latéralement sur un rail. Le rendu est épuré et graphique, "
                "adapté aux grandes ouvertures comme à la séparation d'espaces.",
        "sections": [
            {
                "h": "Une réponse aux grandes baies",
                "p": ["Sur une large baie vitrée, les panneaux glissent les uns derrière "
                      "les autres et se rangent sur un côté, libérant l'ouverture. Les "
                      "lignes verticales des panneaux accentuent la hauteur sous "
                      "plafond et donnent une impression d'espace."],
            },
            {
                "h": "Aussi une cloison mobile",
                "p": ["Hors fenêtre, le même système sert à délimiter un espace sans "
                      "construire : séparer un coin bureau dans un séjour, masquer un "
                      "rangement ou créer une zone plus intime dans une grande pièce, "
                      "avec la possibilité de tout rouvrir en un geste."],
            },
            {
                "h": "Tissus et combinaisons",
                "ul": [
                    "Voilages translucides pour filtrer la lumière et garder la clarté",
                    "Tissus occultants pour l'intimité et la protection solaire",
                    "Alternance de matières et de coloris d'un panneau à l'autre",
                    "Rails à deux, trois ou quatre voies selon la largeur à couvrir",
                ],
            },
        ],
    },

    # ----------------------------------------------------- stores extérieurs
    "stores-exterieurs": {
        "meta": "Stores et aménagements extérieurs sur mesure au Maroc : pergolas, "
                "parasols, toiles tendues, abris de voiture et moustiquaires.",
        "lead": "Une protection posée à l'extérieur arrête le rayonnement solaire avant "
                "qu'il n'atteigne le vitrage. C'est la manière la plus efficace de "
                "réduire la chaleur — et de rendre une terrasse réellement utilisable "
                "en été.",
        "sections": [
            {
                "h": "Pourquoi l'extérieur est plus efficace",
                "p": ["Un store intérieur intercepte la chaleur une fois qu'elle est "
                      "déjà entrée dans la pièce. Une protection extérieure l'arrête en "
                      "amont : la différence de température ressentie derrière une "
                      "grande baie vitrée est nettement plus marquée, et la "
                      "climatisation fonctionne d'autant moins."],
            },
            {
                "h": "Nos solutions extérieures",
                "ul": [
                    "Pergolas : structures fixes ou bioclimatiques pour terrasses et jardins",
                    "Parasols : ombrage mobile pour terrasses, restaurants et espaces de détente",
                    "Toiles tendues : grandes surfaces ombragées, formes libres",
                    "Abris de voiture : protection contre le soleil, la poussière et les intempéries",
                    "Moustiquaires : ouvrir les fenêtres sans laisser entrer les insectes",
                ],
            },
            {
                "h": "Résister au climat et au vent",
                "p": ["Une installation extérieure subit le soleil, la chaleur, la "
                      "poussière et le vent, et en bord de mer, l'air salin. Nous "
                      "sélectionnons des toiles traitées anti-UV et des structures "
                      "dimensionnées pour le site, avec les fixations adaptées au "
                      "support existant."],
            },
        ],
    },

    "pergolas": {
        "meta": "Pergolas sur mesure au Maroc : pergolas bioclimatiques à lames "
                "orientables, pergolas toile et structures aluminium pour terrasses.",
        "lead": "La pergola transforme une terrasse en pièce à vivre supplémentaire : "
                "un espace ombragé, ventilé et utilisable une grande partie de l'année, "
                "au lieu d'une surface abandonnée dès les premières chaleurs.",
        "sections": [
            {
                "h": "La pergola bioclimatique",
                "p": ["Sa toiture est constituée de lames orientables. Fermées, elles "
                      "protègent du soleil et de la pluie ; ouvertes, elles laissent "
                      "circuler l'air et évacuent la chaleur accumulée. On règle "
                      "l'ombre et la ventilation au fil de la journée, ce qu'une "
                      "toiture fixe ne permet pas."],
            },
            {
                "h": "Les autres configurations",
                "ul": [
                    "Pergola à toile rétractable : ombre à la demande, structure plus légère",
                    "Pergola adossée : prend appui sur la façade, idéale en prolongement du séjour",
                    "Pergola autoportée : implantée librement dans le jardin ou près de la piscine",
                ],
            },
            {
                "h": "Options de confort",
                "p": ["La structure peut recevoir des équipements qui étendent son usage "
                      "aux soirées et aux journées venteuses."],
                "ul": [
                    "Stores verticaux latéraux contre le soleil rasant et le vent",
                    "Éclairage LED intégré aux lames ou aux poteaux",
                    "Motorisation des lames avec commande à distance",
                    "Capteurs de pluie et de vent pour une fermeture automatique",
                ],
            },
            {
                "h": "Étude et implantation",
                "p": ["L'orientation, les vents dominants et la nature du support "
                      "déterminent le dimensionnement et les fixations. Nous "
                      "établissons ces points lors de la visite technique avant de "
                      "chiffrer le projet."],
            },
        ],
    },

    "parasols": {
        "meta": "Parasols professionnels et particuliers : parasols déportés, droits et "
                "grandes dimensions pour terrasses, restaurants et hôtels.",
        "lead": "Le parasol reste la solution d'ombrage la plus souple : on le déplace, "
                "on l'oriente selon la course du soleil et on le referme hors saison, "
                "sans aucune construction.",
        "sections": [
            {
                "h": "Déporté ou droit",
                "p": ["Le parasol droit, avec son mât central, est simple et stable — "
                      "mais le pied occupe le centre de l'espace. Le parasol déporté "
                      "reporte le mât sur le côté et libère toute la surface sous la "
                      "toile : c'est le choix naturel au-dessus d'une table, d'un salon "
                      "de jardin ou d'un bord de piscine."],
            },
            {
                "h": "Usage professionnel",
                "p": ["Pour un restaurant, un hôtel ou un espace d'accueil, le parasol "
                      "est un équipement quotidien, ouvert et fermé plusieurs fois par "
                      "jour. Les modèles professionnels utilisent des structures et des "
                      "toiles renforcées, et peuvent recevoir un marquage aux couleurs "
                      "de l'établissement."],
            },
            {
                "h": "Toiles et lestage",
                "p": ["Les toiles sont traitées anti-UV et déperlantes, dans des teintes "
                      "choisies pour limiter l'échauffement sous la toile. Le lestage "
                      "doit être adapté à la taille du parasol et à l'exposition au "
                      "vent : c'est le point le plus souvent sous-estimé, et la "
                      "principale cause de casse."],
            },
        ],
    },

    "toiles-tendues": {
        "meta": "Toiles tendues sur mesure : voiles d'ombrage et structures textiles "
                "pour grandes surfaces, patios, piscines et espaces professionnels.",
        "lead": "La toile tendue couvre de grandes surfaces avec une structure légère. "
                "Tendue entre plusieurs points d'ancrage, elle crée une ombre "
                "généreuse et un geste architectural, sans la masse d'une construction "
                "en dur.",
        "sections": [
            {
                "h": "Des formes libres",
                "p": ["Contrairement à une pergola, la toile tendue n'impose pas un plan "
                      "rectangulaire. Les points d'ancrage peuvent être placés à des "
                      "hauteurs différentes, ce qui donne des surfaces gauches et des "
                      "silhouettes dynamiques adaptées à des espaces de forme "
                      "irrégulière."],
            },
            {
                "h": "Applications",
                "ul": [
                    "Terrasses de restaurants et espaces de restauration extérieurs",
                    "Abords de piscine et espaces de détente",
                    "Patios, cours intérieures et espaces d'accueil",
                    "Aires de jeux, écoles et zones d'attente",
                    "Parkings et zones de stationnement",
                ],
            },
            {
                "h": "Tension et ancrages",
                "p": ["La performance d'une toile tendue tient à sa mise en tension et à "
                      "la solidité de ses ancrages. Une toile insuffisamment tendue "
                      "flotte au vent, s'use prématurément et retient l'eau. Nous "
                      "calculons les efforts et vérifions la capacité des supports "
                      "existants avant la pose."],
            },
        ],
    },

    "abris-de-voiture": {
        "meta": "Abris de voiture sur mesure : carports en aluminium et structures "
                "toile pour protéger les véhicules du soleil et des intempéries.",
        "lead": "L'abri de voiture protège le véhicule du soleil, de la poussière et des "
                "intempéries. Sous le climat marocain, l'exposition prolongée fait "
                "monter fortement la température intérieure et fatigue la peinture, les "
                "plastiques et les joints.",
        "sections": [
            {
                "h": "Une alternative au garage",
                "p": ["L'abri offre l'essentiel de la protection d'un garage sans "
                      "construction lourde ni permis d'une même ampleur. L'installation "
                      "est rapide, l'emprise au sol limitée, et la structure peut "
                      "s'adosser à la façade ou être implantée librement dans la cour."],
            },
            {
                "h": "Configurations",
                "ul": [
                    "Abri adossé : prend appui sur un mur existant, idéal en espace contraint",
                    "Abri autoporté : implanté librement, sur poteaux",
                    "Abri multi-places : structure continue pour plusieurs véhicules",
                    "Couverture en toile tendue, en polycarbonate ou en lames aluminium",
                ],
            },
            {
                "h": "Dimensionnement",
                "p": ["Nous relevons les dimensions du ou des véhicules, la hauteur "
                      "libre nécessaire et les contraintes de manœuvre. Un abri trop "
                      "juste devient vite gênant à l'usage : mieux vaut prévoir la "
                      "largeur d'ouverture des portières dès la conception."],
            },
        ],
    },

    "moustiquaires": {
        "meta": "Moustiquaires sur mesure : enroulables, coulissantes, plissées ou sur "
                "cadre fixe pour fenêtres, portes et baies vitrées.",
        "lead": "La moustiquaire permet de ventiler naturellement en gardant les "
                "fenêtres ouvertes, sans laisser entrer moustiques et insectes. Sur "
                "mesure, elle s'intègre à la menuiserie existante et reste discrète une "
                "fois en place.",
        "sections": [
            {
                "h": "Choisir le bon système",
                "p": ["Le type de moustiquaire dépend de l'ouverture à équiper et de la "
                      "fréquence d'utilisation."],
                "ul": [
                    "Enroulable : la toile s'escamote dans un coffre, idéale pour les fenêtres",
                    "Coulissante : suit le mouvement d'une baie vitrée coulissante",
                    "Plissée : se replie en accordéon, adaptée aux grandes largeurs",
                    "Sur cadre fixe : la solution la plus simple et la plus économique",
                    "Battante : pour les portes de service et les passages fréquents",
                ],
            },
            {
                "h": "Ventiler sans climatiser",
                "p": ["Ouvrir les fenêtres la nuit fait chuter la température intérieure "
                      "sans consommer d'énergie. La moustiquaire est ce qui rend cette "
                      "ventilation nocturne réellement praticable, en particulier dans "
                      "les chambres et les cuisines."],
            },
            {
                "h": "Toiles disponibles",
                "p": ["La toile en fibre de verre est le standard : souple, résistante "
                      "et discrète. Des toiles renforcées existent pour les foyers avec "
                      "animaux, et des mailles plus fines pour arrêter les insectes de "
                      "très petite taille."],
            },
        ],
    },

    # ------------------------------------------------ motorisations, service
    "motorisations-automatismes": {
        "meta": "Motorisation de stores et automatismes : commande à distance, "
                "pilotage centralisé, capteurs soleil et vent, motorisation de "
                "stores existants.",
        "lead": "Motoriser un store, c'est le rendre réellement utilisable : les stores "
                "difficiles d'accès finissent souvent immobiles pendant des mois. Avec "
                "une commande à distance, ils suivent enfin la course du soleil.",
        "sections": [
            {
                "h": "Quand la motorisation s'impose",
                "ul": [
                    "Stores de grandes dimensions, trop lourds à manœuvrer à la main",
                    "Fenêtres en hauteur, en véranda ou derrière un meuble",
                    "Ensembles de plusieurs stores à commander d'un seul geste",
                    "Installations extérieures devant se replier rapidement en cas de vent",
                    "Recherche de confort au quotidien, simplement",
                ],
            },
            {
                "h": "Modes de commande",
                "p": ["Du plus simple au plus intégré, plusieurs niveaux de pilotage "
                      "sont possibles selon vos habitudes et votre installation."],
                "ul": [
                    "Télécommande individuelle ou multicanal",
                    "Point de commande mural, filaire ou radio",
                    "Programmation horaire pour une ouverture et une fermeture automatiques",
                    "Pilotage depuis un smartphone et intégration domotique",
                ],
            },
            {
                "h": "Capteurs soleil, vent et pluie",
                "p": ["Un capteur de vent replie automatiquement un store extérieur "
                      "avant que les rafales ne l'endommagent — c'est une sécurité, pas "
                      "un gadget. Un capteur solaire déploie la protection dès que "
                      "l'ensoleillement dépasse un seuil, ce qui limite la surchauffe "
                      "même en votre absence."],
            },
            {
                "h": "Motoriser un store existant",
                "p": ["Il n'est pas toujours nécessaire de remplacer l'installation. "
                      "Selon le type de store, son état et son mécanisme, l'ajout d'un "
                      "moteur est souvent possible. Nous évaluons cette faisabilité lors "
                      "de la visite technique."],
            },
        ],
    },

    "service": {
        "meta": "Services Alam Stores : conseil, prise de mesures, fabrication sur "
                "mesure, installation, entretien et réparation de stores.",
        "lead": "Notre intervention ne s'arrête pas à la vente. Du premier conseil "
                "jusqu'à l'entretien après installation, nous accompagnons le projet "
                "sur toute sa durée.",
        "sections": [
            {
                "h": "Conseil et étude",
                "p": ["Nous commençons par comprendre l'usage réel de l'espace : "
                      "orientation, heures d'occupation, contraintes de vis-à-vis, "
                      "attentes esthétiques. Cette étape évite le choix le plus "
                      "fréquent et le plus regretté — un produit correct en soi, mais "
                      "inadapté à la pièce."],
            },
            {
                "h": "Prise de mesures",
                "p": ["Les mesures sont relevées sur place. Nous vérifions l'aplomb des "
                      "ouvertures, la nature des supports et les dégagements "
                      "disponibles, autant d'éléments qui déterminent le type de "
                      "fixation et la faisabilité de la pose."],
            },
            {
                "h": "Fabrication et installation",
                "p": ["Les produits sont fabriqués aux dimensions relevées, puis posés "
                      "par nos équipes. L'installation se termine par les réglages, un "
                      "essai complet du fonctionnement et la prise en main du matériel "
                      "avec vous."],
            },
            {
                "h": "Entretien et réparation",
                "p": ["Un store est un équipement mécanique qui s'use. Nous intervenons "
                      "sur les pannes courantes — mécanisme bloqué, chaînette cassée, "
                      "toile déchirée, moteur défaillant — y compris sur des "
                      "installations que nous n'avons pas posées, lorsque les pièces "
                      "restent disponibles."],
            },
            {
                "h": "Projets professionnels",
                "p": ["Pour les hôtels, bureaux, restaurants et commerces, nous "
                      "coordonnons les interventions en fonction de vos contraintes "
                      "d'exploitation, avec des solutions homogènes sur l'ensemble du "
                      "bâtiment et un phasage compatible avec votre activité."],
            },
        ],
    },

    "partenaires": {
        "meta": "Les partenaires et références d'Alam Stores : hôtels, groupes "
                "industriels, restaurants et enseignes équipés en stores et "
                "aménagements sur mesure.",
        "lead": "Nous travaillons aussi bien avec des particuliers qu'avec des "
                "professionnels : hôtels, groupes industriels, restaurants et enseignes "
                "commerciales, pour qui la protection solaire fait partie du niveau de "
                "prestation attendu.",
        "sections": [
            {
                "h": "Des exigences différentes",
                "p": ["Un projet professionnel ne se juge pas seulement sur le produit. "
                      "Comptent tout autant la capacité à équiper un grand nombre "
                      "d'ouvertures de façon homogène, à intervenir sans interrompre "
                      "l'exploitation, et à rester disponible pour la maintenance une "
                      "fois le chantier livré."],
            },
            {
                "h": "Marques et fournisseurs",
                "p": ["Nous nous appuyons sur des fournisseurs reconnus pour les "
                      "composants techniques, notamment pour les motorisations, afin de "
                      "garantir la disponibilité des pièces et la pérennité des "
                      "installations dans le temps."],
            },
        ],
    },

    # ----------------------------------------------------------------- devis
    "devis": {
        "meta": "Demandez votre devis gratuit Alam Stores : stores intérieurs et "
                "extérieurs, pergolas, moustiquaires et motorisations sur mesure au Maroc.",
        "lead": "Décrivez votre projet et nous revenons vers vous avec une proposition "
                "adaptée. L'étude et le devis sont gratuits et sans engagement.",
        "sections": [
            {
                "h": "Ce qui nous aide à répondre précisément",
                "p": ["Plus votre demande est détaillée, plus notre première réponse "
                      "sera juste. Si vous ne disposez pas de toutes ces informations, "
                      "envoyez-nous simplement ce que vous avez : nous compléterons "
                      "ensemble."],
                "ul": [
                    "Le type de produit envisagé, ou simplement le besoin à résoudre",
                    "Le nombre d'ouvertures à équiper et leurs dimensions approximatives",
                    "La pièce concernée et son orientation",
                    "Une préférence entre commande manuelle et motorisée",
                    "Des photos des ouvertures, très utiles pour un premier avis",
                ],
            },
            {
                "h": "La suite",
                "p": ["Nous vous recontactons pour préciser le besoin, puis nous "
                      "convenons d'une visite technique afin de relever les mesures "
                      "exactes. Le devis définitif est établi à l'issue de cette visite, "
                      "sur la base de dimensions réelles et non d'estimations."],
            },
        ],
    },
}
