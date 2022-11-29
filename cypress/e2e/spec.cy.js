/// <reference types="cypress" />

describe("Verime", () => {
    
  beforeEach(() => {

      //Visit landing page and click Launch button
      cy.visit("http://127.0.0.1/verime_redirect");
      cy.url().should("include","/verime.net/ageverify/indexdemo_nocaptcha.php");

      //Define aliases here
      cy.get("[name='phoneno']").as("InsertNumber1");

  });

   // <---------------------------------- New Tests ---------------------------------->

   it("should navigate to verime and insert an invalid number", () => {
    cy.get('@InsertNumber1').type('000543');
    cy.get("form").submit();
    cy.contains("h5","Invalid UK Mobile Number");
  });

   it("should navigate to verime and click cancel", () => {
    cy.get("button").contains("Cancel").click();
    cy.contains("span","Sorry. We cannot establish Age-Verification.");
  });

  it("should type a non age-verified number and alter the URL to bypass the verification", () => {
    cy.get('@InsertNumber1').type('000000000');
    cy.get("form").submit();
    cy.location('search', {timeout: 60000}).should('include', '?av=false');
    cy.contains("span","Sorry. We cannot establish Age-Verification.");

    // Replace URL to bypass verification
    cy.url().invoke('replaceAll','?av=false','?av=true').then(urlValue => cy.forceVisit(urlValue));
    cy.contains("span","Sorry. We cannot establish Age-Verification.");
  });

  // <-------------------------------------------------------------------------------------->

   it("should navigate to verime and type a age-verified number", () => {
      cy.get('@InsertNumber1').type('000000001');
      cy.get("form").submit();
      cy.location('search', {timeout: 60000}).should('include', '?av=true');
      cy.contains("span","Thank you. You are Age-Verified.");
  });

  it("should navigate to verime and type a non age-verified number", () => {
      cy.get('@InsertNumber1').type('000000000');
      cy.get("form").submit();
      cy.location('search', {timeout: 60000}).should('include', '?av=false');
      cy.contains("span","Sorry. We cannot establish Age-Verification.");
  });

  it("should navigate to verime, type a age-verified number and cancel before confirmation", () => {
      cy.get('@InsertNumber1').type('000000001');
      cy.get("form").submit();
      cy.get("button").contains("Cancel").click();
      cy.contains("span","Sorry. We cannot establish Age-Verification.");
  });

  it("should navigate to verime, type a non age-verified number and cancel before confirmation", () => {
      cy.get('@InsertNumber1').type('000000000');
      cy.get("form").submit();
      cy.get("button").contains("Cancel").click();
      cy.contains("span","Sorry. We cannot establish Age-Verification.");
  });

});