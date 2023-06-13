
CREATE INDEX idx_paiements_id_Intervenant ON paiement (id_Intervenant);
CREATE INDEX idx_paiements_id_etab ON paiement (id_etab);
CREATE INDEX idx_paiements_Annee_Univ ON paiement (Annee_Univ);
CREATE INDEX idx_paiements_Semestre ON paiement (Semestre);

 

CREATE INDEX idx_interventions_id_Intervenant ON intervention (id_Intervenant);
CREATE INDEX idx_interventions_id_Etab ON intervention (id_Etab);
CREATE INDEX idx_interventions_Annee_univ ON intervention (Annee_univ);
CREATE INDEX idx_interventions_Semestre ON intervention (Semestre);
CREATE INDEX idx_interventions_Semestre ON intervention (Semestre);
CREATE INDEX idx_interventions_Semestre ON intervention (visa_etb);
CREATE INDEX idx_interventions_uae ON intervention (visa_uae);



CREATE INDEX idx_interventions ON intervention (id_Intervenant, id_Etab, Annee_univ, Semestre);
CREATE INDEX idx_interventions_uae ON intervention (visa_uae);

----------
CREATE INDEX idx_users_email ON users (Type); -----indeex on values that are distincts 

CREATE INDEX idx_enseignants_PPR ON enseignants (id_user);
CREATE INDEX idx_enseignants_etat ON enseignants (etat);
CREATE INDEX idx_enseignants_PPR ON enseignants (etablissement);
CREATE INDEX idx_enseignants_PPR ON enseignants (id_grade);


CREATE INDEX idx_administrateurs_Etablissement ON administrateurs (Etablissement);
CREATE INDEX idx_administrateurs_id_User ON administrateurs (id_User);

CREATE INDEX idx_etablissements_ville ON etablissements (ville);
CREATE INDEX idx_etablissements_Nom ON etablissements (Nom);


