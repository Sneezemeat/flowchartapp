<template>
  <section id="level" v-if="$store.getters.user">
    <b-modal id="tutorial-modal" ref="tutorialmodal" :title="'Level 1: Vorgänge'" ok-only hide-header
             v-if="($store.getters.user.level + 1) === 1" @ok="nextTab($event, 4)">
      <template v-slot:modal-ok>{{ levelModalText }}</template>
      <b-tabs style="font-size: 15px" v-model="levelTab">
        <b-tab title="Einführung">
          <p class="my-2 mt-3">Zu Beginn eine kleine Einführung in Programmablaufpläne (PAPs):</p>
          <p class="my-2">Ein PAP beschreibt Programme durch graphische Elemente. Diese Elemente werden durch
            Pfeile verbunden, welche den Ablauf bzw. die Reihenfolge der Ausführung dieser Elemente
            darstellen</p>
        </b-tab>
        <b-tab title="Start">
          <p class="my-2 mt-3">Die ersten beiden Elemente, welche es in jedem PAP gibt, sind "Start" und
            "Ende". Es muss bei
            einem gültigen PAP immer einen Weg vom Start-Element zum Ende-Element über die Pfeile geben.
            <br>Dieser Weg kann auch indirekt über andere Elemente erfolgen.</p>
        </b-tab>
        <b-tab title="Vorgänge">
          <p class="my-2 mt-3">Das erste Element, welches du selbst erstellen kannst, ist das
            Vorgangs-Element. Dies stellt einen beliebigen Vorgang
            des Programms dar, den du selbst bestimmen kannst <br>(z.B. Berechne 5 * 3)</p>
        </b-tab>
        <b-tab title="Erstellen">
          <p class="my-2 mt-3">Du kannst dieses Element in der Element-Übersicht einfach anklicken, um es zu
            erzeugen. Klicke & halte dann einen der vier Pfeile zum erstellen einer Linie. Diese kannst du
            dann einfach auf ein anderes Element ziehen und dann loslassen.</p>
          <p class="my-2">Versuch einmal selbst einen PAP mit einem Vorgangs-Element zu erstellen</p>
          <p class="my-2" style="font-size: 12px">Du kannst diese Infos auch auf dem
            <b-icon-info-circle></b-icon-info-circle>
            später nochmal aufrufen.
          </p>
          <img :src="level1Gif" alt="Level 1 Tutorial Video" width="100%" height="auto"
               class="d-none d-sm-none d-md-none d-lg-block d-xl-block"/>
        </b-tab>
      </b-tabs>
    </b-modal>
    <b-modal id="tutorial-modal" ref="tutorialmodal" :title="'Level 2: Unterprogramme'" ok-only hide-header
             v-if="($store.getters.user.level + 1) === 2" @ok="nextTab($event, 2)">
      <template v-slot:modal-ok>{{ levelModalText }}</template>
      <b-tabs style="font-size: 15px" v-model="levelTab">
        <b-tab title="Unterprogramme">
          <p class="my-2">Ein weiteres Element, welches es in PAPs gibt, ist das Unterprogramm-Element.
            <br><br>Einzelne
            Programm-Schnipsel, werden für deinen PAP eventuell zu detalliert oder zu unübersichtlich. <br>Daher
            empfielt es sich, in diesen
            Fällen hier den Vorgang nur kurz zu beschreiben oder ihm einen Namen zu geben, und später einen
            eigenen
            PAP dafür zu erstellen.</p>
        </b-tab>
        <b-tab title="Erstellen">
          <img :src="level2Gif" alt="Level 2 Tutorial Video" width="100%" height="auto"
               class="my-2 d-none d-sm-none d-md-none d-lg-block d-xl-block"/>
          <p class="my-2">Versuch einmal selbst einen PAP mit einem Unterprogramm-Element zu erstellen</p>
        </b-tab>
      </b-tabs>
    </b-modal>
    <b-modal id="tutorial-modal" ref="tutorialmodal" :title="'Level 3: Ein-/ Ausgaben'" @ok="nextTab($event, 2)" ok-only
             hide-header
             v-if="($store.getters.user.level + 1) === 3">
      <template v-slot:modal-ok>{{ levelModalText }}</template>
      <b-tabs style="font-size: 15px" v-model="levelTab">
        <b-tab title="Ein- & Ausgaben">
          <p class="my-2">Die nächsten Elemente, welche wir einführen wollen, sind Ein- & Ausgaben. <br>
            Hier kannst du festlegen, dass ein Benutzer deines Programms eine bestimmte Eingabe machen soll, bevor
            das
            Programm weiter ausgeführt wird. Diese Eingabe kannst du in deinem Programm anschließend verwenden. <br>Oder
            dem Benutzer soll etwas ausgegeben werden (z.B. eine durchgeführte Berechnung als Text).</p>
        </b-tab>
        <b-tab title="Erstellen">
          <img :src="level3Gif" alt="Level 3 Tutorial Video" width="100%" height="auto"
               class="my-2 d-none d-sm-none d-md-none d-lg-block d-xl-block"/>
          <p class="my-2">Versuch einmal selbst einen PAP mit einem Eingabe-Element und einem Ausgabe-Element zu
            erstellen</p>
        </b-tab>
      </b-tabs>
    </b-modal>
    <b-modal id="tutorial-modal" ref="tutorialmodal" :title="'Level 4: Bedingungen'" @ok="nextTab($event, 2)" ok-only
             hide-header
             v-if="($store.getters.user.level + 1) === 4">
      <template v-slot:modal-ok>{{ levelModalText }}</template>
      <b-tabs style="font-size: 15px" v-model="levelTab">
        <b-tab title="Bedingungen">
          <p class="my-2">Ein sehr wichtiges Konzept bei der Programmierung sind Bedingungen.<br>
            Einzelne Programmbereiche sollen manchmal nur ausgeführt werden, wenn eine bestimmte Bedingung
            eingetreten ist. <br>
            Zum Beispiel kann dem Nutzer eine Rechenaufgabe gestellt werden, und je nachdem ob die Lösung der
            Aufgabe richtig war, soll das Programm anders reagieren<br><br>
            Die einzelnen Fälle einer Bedingung werden durch Linien zu anderen Elementen dargestellt. Diese Linien
            kannst du im Bearbeiten-Menü beschriften.</p>
        </b-tab>
        <b-tab title="Erstellen">
          <img :src="level4Gif" alt="Level 4 Tutorial Video" width="100%" height="auto"
               class="my-2 d-none d-sm-none d-md-none d-lg-block d-xl-block"/>
          <p class="my-2">Versuch einmal selbst einen PAP mit einem Bedingungs-Element zu erstellen</p>
        </b-tab>
      </b-tabs>
    </b-modal>
    <b-modal id="tutorial-modal" ref="tutorialmodal" :title="'Level 5: Schleifen'" @ok="nextTab($event, 2)" ok-only
             hide-header
             v-if="($store.getters.user.level + 1) === 5">
      <template v-slot:modal-ok>{{ levelModalText }}</template>
      <b-tabs style="font-size: 15px" v-model="levelTab">
        <b-tab title="Schleifen">
          <p class="my-2">Das letzte Level wird dir die Verwendung von Schleifen zeigen.<br>
            Oft müssen in der Programmierung Programmbereiche mehr als einmal durchgeführt werden. Hierfür werden
            Schleifen verwendet. Dabei kannst du eine Bedingung angeben, wie lange diese Schleife durchgeführt
            werden soll. Zum Beispiel soll ein Nutzer so lange eine Zahl eingeben können, bis das Ergebnis richtig
            ist. Oder ein Nutzer hat 3 Versuche die Lösung richtig einzugeben<br><br>
            Du kannst Elemente einfach in eine Schleife hineinziehen. Wenn die Schleife mehr als ein Element
            enthalten soll, kannst du die Schleife vergrößeren, indem du ein Element an den unteren oder an den
            rechten Rand ziehst. Du kannst Elemente natürlich auch wieder aus der Schleife herausziehen</p>
        </b-tab>
        <b-tab title="Erstellen">
          <img :src="level5Gif" alt="Level 5 Tutorial Video" width="100%" height="auto"
               class="my-2 d-none d-sm-none d-md-none d-lg-block d-xl-block"/>
          <p class="my-2">Versuch einmal selbst einen PAP mit einem Schleifen-Element zu erstellen</p>
        </b-tab>
      </b-tabs>
    </b-modal>
    <b-modal id="error-modal" ref="errormodal" title="Fehler im PAP" ok-only>
      <p class="my-2">Dein PAP sieht leider noch nicht ganz richtig aus</p>
      <p class="my-2">Gibt es eine Verbindung vom Start-Element zum Ende-Element?</p>
      <p class="my-2" v-if="($store.getters.user.level + 1) === 1">Hast du ein Vorgangs-Element erstellt?</p>
      <p class="my-2" v-if="($store.getters.user.level + 1) === 2">Hast du ein Unterprogramm-Element erstellt?</p>
      <p class="my-2" v-if="($store.getters.user.level + 1) === 3">Hast du ein Eingabe- und ein Ausgabe-Element
        erstellt?</p>
      <p class="my-2" v-if="($store.getters.user.level + 1) === 4">Hast du ein Bedingungs-Element erstellt?</p>
      <p class="my-2" v-if="($store.getters.user.level + 1) === 5">Hast du ein Schleifen-Element erstellt?</p>
      <p class="mt-4">Schau dir deinen PAP nochmal an, und probiere es dann nochmal!</p><br>
    </b-modal>
    <b-modal id="success-modal" ref="successmodal" title="Geschafft!" ok-only @ok="nextLevel">
      <p class="my-2">Du hast das Level erfolgreich abgeschlossen!</p>
    </b-modal>
    <Diagram :isTutorial="true" @checkLevel="(start) => {checkLevel(start)}" ref="diagram"></Diagram>
  </section>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import Diagram from "../views/Diagram.vue";
import ElementData from "../DTO/ElementData";
import axios from "axios";

@Component({
  components: {Diagram}
})
export default class Level extends Vue {

  private exploredElements = [];
  private exploredConnections = [];
  private level1Gif = require('../assets/level1.gif?name="level1.gif"').default;
  private level2Gif = require('../assets/level2.gif?name="level2.gif"').default;
  private level3Gif = require('../assets/level3.gif?name="level3.gif"').default;
  private level4Gif = require('../assets/level4.gif?name="level4.gif"').default;
  private level5Gif = require('../assets/level5.gif?name="level5.gif"').default;
  private levelTab: number;
  private levelModalText = "Weiter";

  constructor($options?) {
    super($options);
    this.levelTab = 0;
  }

  mounted() {
    this.$bvModal.show('tutorial-modal');
  }

  nextTab(modalEvent, max) {
    if (this.levelTab < (max - 2)) {
      modalEvent.preventDefault();
      this.levelTab++;
    } else if (this.levelTab === (max - 2)) {
      modalEvent.preventDefault();
      this.levelTab++;
      this.levelModalText = "OK";
    } else {
      this.$bvModal.hide('tutorial-modal');
      this.levelTab = 0;
      this.levelModalText = "Weiter";
    }
  }

  checkLevel(elements: ElementData[]) {
    this.exploredConnections = [];
    this.exploredElements = [];

    const start = elements.filter(e => {
      const element = e as ElementData;
      return element.text === 'Start';
    })[0];

    if (this.$store.getters.user.level < 4) {
      const result = this.BFS(start);
      const check = this.checkElement();

      if (result !== null && check) {
        this.$bvModal.show('success-modal');
      } else {
        this.$bvModal.show('error-modal');
      }
    } else {
      this.$bvModal.show('success-modal');
    }
  }

  checkElement() {
    const types = [];

    switch (this.$store.getters.user.level + 1) {
      case 1:
        types.push('Process');
        break;
      case 2:
        types.push('SubProcess');
        break;
      case 3:
        types.push('Input');
        types.push('Output');
        break;
      case 4:
        types.push('Condition');
        break;
      default:
        break;
    }
    let check = true;
    types.forEach(type => {
      if (this.exploredElements.filter(e => {
        const element = e as ElementData;
        return element.type === type;
      }).length === 0) {
        check = false;
      }
    });
    return check;
  }


  BFS(element) {
    const queue = [];
    queue.push(element);
    this.exploredElements.push(element);
    while (queue.length > 0) {
      const queuedElement = queue.shift();
      if (queuedElement.text === 'Ende') {
        return queuedElement;
      }
      queuedElement.connections.forEach(connection => {
        const adjacentElement = connection.element;
        if (this.exploredElements.indexOf(adjacentElement) === -1) {
          this.exploredElements.push(adjacentElement);
          queue.push(adjacentElement);
        }
      });
    }
    return null;
  }


  nextLevel() {
    if (!JSON.parse(localStorage.local)) {
      axios.get("/user/level/increase", {
        headers: {'Content-Type': 'application/json'},
        withCredentials: true
      })
          .then(() => {
            const diagram = this.$refs.diagram as Diagram;
            diagram.save();
            this.$router.push({name: 'List'});
            this.$store.getters.user.level++;
          });
    } else {
      const user = JSON.parse(localStorage.user);
      user.level++;
      localStorage.user = JSON.stringify(user);
      const diagram = this.$refs.diagram as Diagram;
      diagram.save();
      this.$router.push({name: 'List'});
      this.$store.getters.user.level++;
    }
  }

}
</script>

<style scoped>

</style>