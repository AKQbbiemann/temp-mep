import { ref } from "vue";

const events = ref([]);

// Function to convert hex color to a valid CSS class name
const colorToClassName = (bgColor = "#f8f8f8", color = "#666666") => {
  return `bg-${bgColor.replace("#", "")}-color-${color.replace("#", "")}`;
};

// Function to inject dynamic styles into the document head
const injectDynamicStyles = (bgColor, color, type) => {
  const style = document.createElement("style");
  const uniqueClass = colorToClassName(bgColor, color);

  style.innerHTML = `
    .${uniqueClass} {
      background-color: ${bgColor};
      color: ${color};
      z-index: ${type === "phaze" ? 2 : 1};
    }
  `;

  document.head.appendChild(style);
};

// Function to generate dynamic events
const generateEvents = (requirements) => {
  requirements.forEach((requirement) => {
    const fromDate = Date.parse(requirement.start);
    const toDate = Date.parse(requirement.end);
    const bgColor = requirement.bgColor;
    const color = requirement.color;
    const dayViewPeriod = requirement.dayViewPeriod;
    const title = requirement.title;
    const content = requirement.content;

    const days = Math.round((toDate - fromDate) / (1000 * 60 * 60 * 24));

    for (let i = 0; i <= days; i++) {
      const eventDate = new Date(fromDate);
      eventDate.setDate(eventDate.getDate() + i);

      const uniqueClass = colorToClassName(bgColor, color);

      events.value.push({
        start: new Date(eventDate.setHours(dayViewPeriod[0], 0)),
        end: new Date(eventDate.setHours(dayViewPeriod[1], 0)),
        title: i === 0 || i === days ? title : "-",
        content: i === 0 || i === days ? content : "-",
        class: uniqueClass,
        background: true,
        resizable: false,
      });
    }

    injectDynamicStyles(bgColor, color, requirement.type);
  });
};

const useEventGenerator = () => {
  return {
    events,
    generateEvents,
  };
};

export default useEventGenerator;
