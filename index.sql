



 





CREATE INDEX idx_interventions ON interventions (id_Intervenant, id_Etab, Annee_univ, Semestre);
CREATE INDEX idx_interventionssemestre ON interventions (id_Intervenant, id_Etab, Annee_univ);
CREATE INDEX idx_interventions_uae ON interventions (visa_uae);
CREATE INDEX idx_paiements ON paiements (id_Intervenant, id_Etab, Annee_univ, Semestre);
CREATE INDEX idx_paiements_more ON paiements (id_Intervenant, id_Etab, Annee_univ);

----------
CREATE INDEX idx_users_email ON users (Type); -----indeex on values that are distincts 

CREATE INDEX idx_enseignants_PPR ON enseignants (id_user);
CREATE INDEX idx_enseignants_etat ON enseignants (etat);
CREATE INDEX idx_enseignants_etab ON enseignants (etablissement);






